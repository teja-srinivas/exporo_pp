<?php

namespace App\Http\Controllers;

use App\Models\CommissionType;
use App\Models\Project;
use Illuminate\Http\Request;

class CommissionTypeController extends Controller
{


    public function index()
    {
        $this->authorize('viewAny', CommissionType::class);

        return view('commissions.types.index', [
            'types' => CommissionType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('commissions.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $data['is_project_type'] = $request->has('is_project_type');

        CommissionType::query()->create($data);

        flash_success('Provisionstyp wurde angelegt');

        return redirect()->route('commissions.types.index');
    }

    /**
     * @param CommissionType $type
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionType $type)
    {
        $projects = $type->is_project_type
            ? $type->projects()->get()->map(function (Project $project) {
                return [
                    'id' => $project->id,
                    'project' => $project->description,
                    'createdAt' => optional($project->created_at)->format('Y-m-d'),
                    'links' => [
                        'self' => route('projects.show', $project),
                    ],
                ];
            })->values()
            : [];

        return response()->view('commissions.types.show', compact('type', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CommissionType $type
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionType $type)
    {
        return response()->view('commissions.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CommissionType $type
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, CommissionType $type)
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $data['is_project_type'] = $request->has('is_project_type');

        $type->fill($data)->save();

        flash_success('Provisionstyp wurde aktualisiert');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
