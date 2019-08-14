<?php

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Commission;
use App\Models\Investment;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Commission::class);

        $query = Commission::query()->with([
            'user:id,last_name,first_name',
            'user.details' => function (HasOne $query) {
                return $query->select(['id', 'vat_included']);
            },
            'childUser:id,last_name,first_name',
            'model',
        ]);

        $results = $this->applyFilter($query, $request);

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
     * @throws AuthorizationException
     */
    public function store(Request $request, CalculateCommissionsService $service)
    {
        $this->authorize('create', Commission::class);

        $data = $this->validate($request, [
            'userId' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'note.public' => ['sometimes'],
            'note.private' => ['sometimes'],
        ]);

        /** @var User $user */
        $user = User::query()->findOrFail($data['userId']);

        /** @var Contract $contract */
        $contract = $user->contract()->firstOrFail();

        $sums = $service->calculateNetAndGross($contract, (float) $data['amount']);

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
    public function show(Commission $commission)
    {
        return CommissionResource::make($commission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Commission  $commission
     * @param CalculateCommissionsService $service
     * @return void
     * @throws Throwable
     */
    public function update(Request $request, Commission $commission, CalculateCommissionsService $service)
    {
        $this->authorize('update', $commission);

        static $lookup = [
            'note.public' => 'note_public',
            'note.private' => 'note_private',
            'onHold' => 'on_hold',
            'net' => 'net',
            'gross' => 'gross',
        ];

        // Remap keys using the lookup table
        $remapped = collect($request->all())->mapWithKeys(function ($value, $key) use ($lookup) {
            return [($lookup[$key] ?? $key) => $value];
        })->all();

        $commission->fill($remapped);

        // Update special values
        if (isset($remapped['rejected'])) {
            $commission->reject($remapped['rejected'] === true ? $request->user() : null);
        }

        if (isset($remapped['reviewed'])) {
            $commission->review($remapped['reviewed'] === true ? $request->user() : null);
        }

        if (isset($remapped['amount'])) {
            $commission->fill($service->calculateNetAndGross(
                $commission->user->contract,
                (float) $remapped['amount']
            ));
        }

        $commission->saveOrFail();
    }

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
     * @param  int  $commission
     * @param  Request  $request
     * @return void
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $commission, Request $request)
    {
        if ($commission > 0) {
            $commission = (new Commission)->resolveRouteBinding($commission);
            $this->authorizeResource($commission);
            $commission->delete();
            return;
        }

        // Delete and refresh commissions
        $this->authorize('delete', Commission::class);
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
            ->when(true, function (Builder $query) use ($fields) {
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
            ->when($fields->has('user'), function (Builder $query) use ($fields, $forUpdate) {
                $user = $fields->get('user');

                if (! empty($user->filter)) {
                    $query->forUser($user->filter);
                }

                if (! $forUpdate && ! empty($user->order)) {
                    $query->orderBy('commissions.user_id', $user->order);
                }
            })
            ->when($fields->filters('model'), function (Builder $query) use ($fields) {
                $lowercaseName = mb_convert_case($fields->get('model')->filter, MB_CASE_LOWER);
                $quotedName = DB::connection()->getPdo()->quote('%'.$lowercaseName.'%');

                $projectIds = Project::query()
                    ->whereRaw('LOWER(description) LIKE '.$quotedName)
                    ->select('id');

                $query->whereIn('investments.project_id', $projectIds);
            })
            ->when($fields->has('money'), function (Builder $query) use ($forUpdate, $fields) {
                $money = $fields->get('money');

                if (! $forUpdate && ! empty($money->order)) {
                    $query->orderBy('net', $money->order);
                }

                // Match any valid where clause for SQL
                if (preg_match('/(>=|>|=|<|<=|!=)?\s*(\d+)/', $money->filter, $matches) > 0) {
                    $query->where('gross', $matches[1] ?: '=', $matches[2]);
                }
            })
            ->when(! $fields->filters('rejected'), function (Builder $query) {
                $query->isOpen();
            })
            ->when($fields->filters('overhead'), function (Builder $query) use ($fields) {
                $query->where('child_user_id', $fields->get('overhead')->filter === 'true' ? '>' : '=', 0);
            })
            ->when($fields->filters('type'), function (Builder $query) use ($fields) {
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
            ->when($fields->filters('id'), function (Builder $query) use ($fields) {
                $query->where('commissions.model_id', $fields->get('id')->filter);
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
            : (new Commission)->newCollection();

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
