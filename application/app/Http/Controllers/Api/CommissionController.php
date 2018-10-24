<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Commission as CommissionResource;
use App\Models\Commission;
use App\Models\Investment;
use App\Models\Project;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('list', Commission::class);

        $query = Commission::query()->with([
            'user:id,last_name,first_name',
            'model',
        ]);

        $results = $this->applyFilter($query, $request);

        // Run a custom pagination that also sums the "net"
        // and "gross" so we don't have to do 3 queries
        $results = $this->runCustomPagination($results);

        // Eager load morphTo relationships
        $results->loadMorph('model', [
            \App\Models\Investment::MORPH_NAME => [
                'investor.id,last_name,first_name',
                'project:id,name,schema_id',
                'project.schema:id,formula',
            ],
        ]);

        // Return a JSON resource
        return CommissionResource::collection($results)->additional([
            'meta' => [
                'totalNet' => $results->totalNet,
            ],
        ]);
    }

    private function parseSortAndFilter(Request $request)
    {
        // Filter is a simple array
        $filters = $request->get('filter', []);

        // Sort works via column names, descending marked via "-" prefix
        // http://jsonapi.org/format/#fetching-sorting
        $sort = collect(explode(',', $request->get('sort', '')))
            ->filter()
            ->mapWithKeys(function ($column) {
                $descending = strpos($column, '-') === 0;
                $name = $descending ? substr($column, 1) : $column;

                return [
                    $name => $descending ? 'desc' : 'asc',
                ];
            });

        return collect(array_unique(array_merge(
            array_keys($filters),
            array_keys($sort->all())
        )))->mapWithKeys(function ($column) use ($filters, $sort) {
            return [
                $column => [
                    'filter' => $filters[$column] ?? '',
                    'order' => $sort[$column] ?? '',
                ],
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Commission $commission
     * @return void
     * @throws \Throwable
     */
    public function update(Request $request, Commission $commission)
    {
        static $lookup = [
            'note.public' => 'note_public',
            'note.private' => 'note_private',
            'onHold' => 'on_hold',
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

        $commission->saveOrFail();
    }

    public function updateMultiple(Request $request)
    {
        $userId = $request->user()->id;

        $now = now();
        $values = [
            'commissions.' . Model::CREATED_AT => $now,
            'commissions.' . Model::UPDATED_AT => $now,
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

        // Don't use "update" directly, so it doesn't update the updated_at column
        $this->applyFilter(Commission::query(), $request, true)->toBase()->update($values);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission)
    {
        //
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
        $columns = $this->parseSortAndFilter($request);

        return $query
            ->where('on_hold', $columns->has('onHold'))
            ->when(true, function (Builder $query) use ($columns) {
                if ($columns->has('rejected')) {
                    $query->whereNotNull('rejected_at');
                    $query->whereNull('bill_id');
                } else {
                    $query->whereNull('rejected_at');
                }

                if ($columns->has('reviewed')) {
                    $query->whereNotNull('reviewed_at');
                } else {
                    $query->whereNull('reviewed_at');
                }
            })
            ->when($columns->has('user'), function (Builder $query) use ($columns, $forUpdate) {
                $user = $columns['user'];

                if (!empty($user['filter'])) {
                    $query->forUser($user['filter']);
                }

                if (!$forUpdate && !empty($user['order'])) {
                    $query->orderBy('commissions.user_id', $user['order']);
                }
            })
            ->when($columns->has('model'), function (Builder $query) use ($columns) {
                $lowercaseName = mb_convert_case($columns['model']['filter'], MB_CASE_LOWER);
                $quotedName = DB::connection()->getPdo()->quote('%' . $lowercaseName . '%');

                $projectIds = Project::query()
                    ->whereRaw('LOWER(description) LIKE ' . $quotedName)
                    ->select('id');

                $query->whereIn('investments.project_id', $projectIds);
            })
            ->when($columns->has('money'), function (Builder $query) use ($forUpdate, $columns) {
                if (!$forUpdate && !empty($columns['money']['order'])) {
                    $query->orderBy('net', $columns['money']['order']);
                }

                // Match any valid where clause for SQL
                if (preg_match('/(>=|>|=|<|<=|!=)?\s*(\d+)/', $columns['money']['filter'], $matches) > 0) {
                    $query->where('net', $matches[1] ?: '=', $matches[2]);
                }
            })
            ->when(!$columns->has('rejected'), function (Builder $query) {
                $query->isOpen();
            })
            ->when(
                $columns->has('type') && !empty($columns['type']['filter']),
                function (Builder $query) use ($columns) {
                    $type = $columns['type']['filter'];

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
                }
            )
            ->when($columns->has('id'), function (Builder $query) use ($columns) {
                $query->where('commissions.model_id', $columns['id']['filter']);
            })
            ->isAcceptable()
            ->select('commissions.*');
    }

    private function runCustomPagination(Builder $query): LengthAwarePaginator
    {
        static $pageName = 'page';
        static $perPage = 25;

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
                'totalNet' => (float) $totals->net,
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
            ->selectRaw('SUM(net) as net')
            ->get()->first();
    }
}
