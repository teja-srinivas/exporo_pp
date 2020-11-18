<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Project;
use App\Models\Commission;
use App\Models\Investment;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Http\Helper\Request\FieldParser;
use Illuminate\Database\Eloquent\Builder;
use App\Services\CalculateCommissionsService;
use App\Console\Commands\CalculateCommissions;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Http\Resources\Commission as CommissionResource;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Commission::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return $this->getCommissionResource();
    }

    /**
     * Display a listing of the resource for pending dataset.
     *
     * @return AnonymousResourceCollection
     */
    public function pending(): AnonymousResourceCollection
    {
        return $this->getCommissionResource(true);
    }

    private function getCommissionResource(bool $pending = false): AnonymousResourceCollection
    {
        $query = $pending
            ? Commission::query()
                ->where('pending', true)
                ->with([
                    'user:id,last_name,first_name',
                    'user.details' => static function (HasOne $query) {
                        return $query->select(['id', 'vat_included']);
                    },
                    'childUser:id,last_name,first_name',
                    'model',
                ])
            : Commission::query()->with([
                'user:id,last_name,first_name',
                'user.details' => static function (HasOne $query) {
                    return $query->select(['id', 'vat_included']);
                },
                'childUser:id,last_name,first_name',
                'model',
            ]);

        $results = $this->applyFilter($query, request());

        // Run a custom pagination that also sums the "net"
        // and "gross" so we don't have to do 3 queries
        $results = $this->runCustomPagination($results);

        // Eager load morphTo relationships
        $results->loadMorph('model', [
            Investment::class => [
                'investor:id,last_name,first_name',
                'project.schema:id,formula',
            ],
        ]);

        // Return a JSON resource
        return CommissionResource::collection($results)->additional([
            'meta' => [
                'totalGross' => $results->totalGross,
            ],
        ]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  CalculateCommissionsService $service
     * @return void
     * @throws ValidationException
     */
    public function store(Request $request, CalculateCommissionsService $service)
    {
        $data = $this->validate($request, [
            'userId' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'note.public' => ['sometimes'],
            'note.private' => ['sometimes'],
        ]);

        /** @var User $user */
        $user = User::query()->findOrFail($data['userId']);

        $sums = $service->calculateNetAndGross($user->productContract, (float) $data['amount']);

        Commission::query()->forceCreate($sums + [
            'model_type' => Commission::TYPE_CORRECTION,
            'user_id' => $user->getKey(),
            'child_user_id' => 0,
            'bonus' => 0,
            'note_public' => Arr::get($data, 'note.public', null),
            'note_private' => Arr::get($data, 'note.private', null),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Commission  $commission
     * @return CommissionResource
     */
    public function show(Commission $commission): CommissionResource
    {
        return CommissionResource::make($commission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Commission  $commission
     * @param CalculateCommissionsService $service
     * @return CommissionResource
     * @throws Throwable
     */
    public function update(
        Request $request,
        Commission $commission,
        CalculateCommissionsService $service
    ): CommissionResource {
        static $lookup = [
            'note.public' => 'note_public',
            'note.private' => 'note_private',
            'onHold' => 'on_hold',
        ];

        if (!$commission->user->hasActiveContract()) {
            throw new HttpException(
                Response::HTTP_PRECONDITION_FAILED,
                'User does not have an active contract'
            );
        }

        // Remap keys using the lookup table
        $remapped = collect($request->all())->mapWithKeys(static function ($value, $key) use ($lookup) {
            return [($lookup[$key] ?? $key) => $value];
        })->all();

        $commission->fill(Arr::only($remapped, array_values($lookup)));

        // Update special values
        if (isset($remapped['rejected'])) {
            $commission->reject($remapped['rejected'] === true ? $request->user() : null);
        }

        if (isset($remapped['reviewed'])) {
            $commission->review($remapped['reviewed'] === true ? $request->user() : null);
        }

        if (isset($remapped['amount']) && $remapped['amount'] !== null) {
            $commission->forceFill($service->calculateNetAndGross(
                $commission->user->productContract,
                (float) $remapped['amount']
            ));
        }

        if (isset($remapped['bonus']) && $remapped['bonus'] !== null) {
            $user = $commission->user;
            $sum = $service->calculateSum($commission->investment, (float) $remapped['bonus']);
            $netGross = $service->calculateNetAndGross($user->productContract, $sum);

            $commission->forceFill($netGross + ['bonus' => (float) $remapped['bonus']]);
        }

        $commission->saveOrFail();

        return CommissionResource::make($commission);
    }

    /**
     * @param  Request  $request
     * @throws AuthorizationException
     */
    public function updateMultiple(Request $request)
    {
        $this->authorize('update', Commission::class);

        $userId = $request->user()->id;

        $now = now();
        $values = [
            'commissions.'.Model::CREATED_AT => $now,
            'commissions.'.Model::UPDATED_AT => $now,
        ];

        if ($request->has('onHold')) {
            $values['on_hold'] = $request->get('onHold');
        }

        if ($request->has('rejected')) {
            if ($request->get('rejected') === true) {
                $values['rejected_at'] = $now;
                $values['rejected_by'] = $userId;
            } else {
                $values['rejected_at'] = null;
                $values['rejected_by'] = null;
            }
        }

        if ($request->has('reviewed')) {
            if ($request->get('reviewed') === true) {
                $values['reviewed_at'] = $now;
                $values['reviewed_by'] = $userId;
            } else {
                $values['reviewed_at'] = null;
                $values['reviewed_by'] = null;
            }
        }

        if ($request->has('reset')) {
            $values['on_hold'] = false;
            $values['reviewed_at'] = null;
            $values['reviewed_by'] = null;
        }

        // Don't use "update" directly, so it doesn't update the updated_at column
        $this->applyFilter(Commission::query(), $request, true)->toBase()->update($values);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Commission  $commission
     * @return void
     * @throws Exception
     */
    public function destroy(Commission $commission)
    {
        $commission->delete();
    }

    /**
     * @param  Request  $request
     * @throws AuthorizationException
     */
    public function destroyMultiple(Request $request)
    {
        $this->authorize('delete', Commission::class);

        // Delete and refresh commissions
        $this->applyFilter(Commission::query(), $request)->delete();

        Artisan::call(CalculateCommissions::class);
    }

    /**
     * @param Builder $query
     * @param Request $request
     * @param bool $forUpdate Enables performance improvements when we're executing an UPDATE query
     * @return Builder
     */
    private function applyFilter(
        Builder $query,
        Request $request,
        bool $forUpdate = false
    ): Builder {
        $fields = FieldParser::fromRequest($request);

        return $query
            ->where('commissions.user_id', '>', 0)
            ->where('on_hold', $fields->has('onHold'))
            ->when(true, static function (Builder $query) use ($fields) {
                if ($fields->filters('rejected')) {
                    $query->whereNotNull('rejected_at');
                    $query->whereNull('bill_id');
                } else {
                    $query->whereNull('rejected_at');
                }

                if ($fields->filters('reviewed')) {
                    $query->whereNotNull('reviewed_at');
                } else {
                    $query->whereNull('reviewed_at');
                }
            })
            ->when($fields->has('user'), static function (Builder $query) use ($fields, $forUpdate) {
                $user = $fields->get('user');

                if (! empty($user->filter)) {
                    $query->forUser($user->filter);
                }

                if ($forUpdate || empty($user->order)) {
                    return;
                }

                $query->orderBy('commissions.user_id', $user->order);
            })
            ->when($fields->filters('model'), static function (Builder $query) use ($fields) {
                $lowercaseName = mb_convert_case($fields->get('model')->filter, MB_CASE_LOWER);
                $quotedName = DB::connection()->getPdo()->quote('%'.$lowercaseName.'%');

                $projectIds = Project::query()
                    ->whereRaw('LOWER(description) LIKE '.$quotedName)
                    ->select('id');

                $query->whereIn('investments.project_id', $projectIds);
            })
            ->when($fields->has('money'), static function (Builder $query) use ($forUpdate, $fields) {
                $money = $fields->get('money');

                if (! $forUpdate && ! empty($money->order)) {
                    $query->orderBy('net', $money->order);
                }

                // Match any valid where clause for SQL
                if (preg_match('/(>=|>|=|<|<=|!=)?\s*(\d+)/', $money->filter, $matches) <= 0) {
                    return;
                }

                $query->where('gross', $matches[1] ?: '=', $matches[2]);
            })
            ->when(! $fields->filters('rejected'), static function (Builder $query) {
                $query->isOpen();
            })
            ->when($fields->filters('overhead'), static function (Builder $query) use ($fields) {
                $query->where('child_user_id', $fields->get('overhead')->filter === 'true' ? '>' : '=', 0);
            })
            ->when($fields->filters('type'), static function (Builder $query) use ($fields) {
                $type = $fields->get('type')->filter;

                switch ($type) {
                    case 'first-investment':
                        $query->where('model_type', Investment::MORPH_NAME);
                        $query->where('investments.is_first_investment', true);

                        break;
                    case 'further-investment':
                        $query->where('model_type', Investment::MORPH_NAME);
                        $query->where('investments.is_first_investment', false);

                        break;
                    default:
                        $query->where('model_type', $type);
                }
            })
            ->when($fields->filters('id'), static function (Builder $query) use ($fields) {
                $query->where('commissions.model_id', $fields->get('id')->filter);
            })
            ->when($fields->filters('legalSetup'), static function (Builder $query) use ($fields) {
                $legalSetup = $fields->get('legalSetup')->filter;

                $legalSetupMap = [
                    'bond' => [
                        'bond',
                        'bondLight',
                        'priip',
                    ],
                    'investment' => [
                        'subordiantedLoan',
                        'investmentLaw2',
                        'investmentLaw2a',
                        'silentParticipation',
                    ],
                ];

                if ($legalSetup !== "null"){
                    $projectIds = Project::whereIn('legal_setup', $legalSetupMap[$legalSetup])
                        ->select('id');
                } else {
                    $projectIds = Project::whereNull('legal_setup')
                        ->select('id');
                }

                $query->whereIn('investments.project_id', $projectIds);
            })
            ->withinRange(
                $fields->get('rangeFrom', Commission::LAUNCH_DATE)->filter,
                $fields->filters('rangeTo') ? $fields->get('rangeTo')->filter : null
            )
            ->select('commissions.*');
    }

    private function runCustomPagination(Builder $query): LengthAwarePaginator
    {
        static $pageName = 'page';
        static $perPage = 35;

        // Mostly copied from the BuildsQueries->paginate() method
        $page = Paginator::resolveCurrentPage($pageName);

        $totals = $this->getPaginationTotals($query);
        $results = $totals->aggregate
            ? $query->forPage($page, $perPage)->get()
            : (new Commission())->newCollection();

        // modified copy of $query->paginator()
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results,
            'total' => $totals->aggregate,
            'perPage' => $perPage,
            'currentPage' => $page,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
                'totalGross' => (float) $totals->gross,
            ],
        ]);
    }

    private function getPaginationTotals(Builder $query)
    {
        // modified copy of $query->toBase()->getCountForPagination();
        // here we can finally add the sum() columns
        return $query->toBase()
            ->cloneWithout(['columns', 'orders', 'limit', 'offset'])
            ->cloneWithoutBindings(['select', 'order'])
            ->selectRaw('count(commissions.id) as aggregate')
            ->selectRaw('SUM(gross) as gross')
            ->get()->first();
    }
}
