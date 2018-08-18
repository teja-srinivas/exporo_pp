<?php

namespace App\Http\Controllers\Api;

use App\Commission;
use App\Http\Controllers\Controller;
use App\Http\Resources\Commission as CommissionResource;
use App\Http\Resources\CommissionCollection;
use App\Http\Resources\PaginatedResource;
use App\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

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
            'model.investor:id,last_name,first_name',
            'model.project:id,name,schema_id',
            'model.project.schema:id,formula',
            'user:id,last_name,first_name',
        ]);

        $this->applyFilter($query, $request);

        return CommissionResource::collection($query->paginate(25));
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
     * @param  \App\Commission  $commission
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
     * @param  \App\Commission $commission
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
        $values = [];
        $now = now();
        $userId = $request->user()->id;

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

        $this->applyFilter(Commission::query(), $request, true)->update($values);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commission  $commission
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
                    $query->where('user_id', $user['filter']);
                }

                if (!$forUpdate && !empty($user['order'])) {
                    $query->orderBy('user_id', $user['order']);
                }
            })
            ->when($columns->has('model'), function (Builder $query) use ($columns) {
                $projectIds = Project::query()
                    ->where('name', 'like', '%' . $columns['model']['filter'] . '%')
                    ->pluck('id');

                $query->join('investments', function (JoinClause $join) use ($projectIds) {
                    $join->on('commissions.model_id', 'investments.id');
                    $join->whereIn('project_id', $projectIds);
                });
            })
            ->when(
                !$forUpdate && $columns->has('money') && !empty($columns['money']['order']),
                function (Builder $query) use ($columns) {
                    $query->orderBy('net', $columns['money']['order']);
                }
            )
            ->isOpen()
            ->isAcceptable($forUpdate);
    }

}
