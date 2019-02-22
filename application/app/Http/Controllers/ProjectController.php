<?php

namespace App\Http\Controllers;

use App\Models\CommissionType;
use App\Models\Project;
use App\Models\Schema;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::query()
                ->with('commissionType', 'schema')
                ->get()
                ->map(function (Project $project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->description,
                        'schema' => optional($project->schema)->name,
                        'type' => optional($project->commissionType)->name,
                        'status' => $project->wasApproved() ? null : '<div class="badge badge-warning">Ausstehend</div>',
                        'createdAt' => optional($project->created_at)->format('Y-m-d'),
                        'launchedAt' => optional($project->launched_at)->format('Y-m-d'),
                        'updatedAt' => optional($project->updated_at)->format('Y-m-d'),
                        'links' => [
                            'self' => route('projects.show', $project),
                        ],
                    ];
                }),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        $schemas = Schema::query()->pluck('name', 'id');

        $commissionTypes = CommissionType::query()
            ->where('is_project_type', true)
            ->pluck('name', 'id');

        return view('projects.show', compact('project', 'schemas', 'commissionTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Project $project)
    {
        $data = $this->validate($request, [
            'accept' => 'nullable|boolean',
            'schema' => 'nullable|numeric|exists:schemas,id',
            'commissionType' => 'nullable|numeric|exists:commission_types,id'
        ]);

        if (isset($data['accept'])) {
            $project->approved_at = now();
            $project->approved()->associate($request->user());
            $project->save();

            flash_success('Das Projekt wurde erfolgreich bestätigt.');
        }

        if (isset($data['schema'])) {
            $project->schema()->associate(Schema::findOrFail($data['schema']));
            $project->save();

            flash_success('Das Schema wurde erfolgreich geändert.');
        }

        if (isset($data['commissionType'])) {
            $project->commissionType()->associate(CommissionType::query()
                ->where('is_project_type', true)
                ->findOrFail($data['commissionType']));

            $project->save();

            flash_success('Der Provisionstyp wurde erfolgreich geändert.');
        }

        return back();
    }
}
