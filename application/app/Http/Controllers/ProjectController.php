<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Schema;
use App\Models\Project;
use App\Rules\GermanUrlRule;
use App\Traits\ProjectTrait;
use Illuminate\Http\Request;
use App\Models\CommissionType;

class ProjectController extends Controller
{
    use ProjectTrait;

    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $selectionIDs = Project::query()
            ->where('in_iframe', true)
            ->get()
            ->map(static function (Project $project) {
                if ((new self())->checkForError($project) !== null) {
                    return false;
                }

                return [
                    'id' => $project->id,
                ];
            })
            ->filter()
            ->pluck('id');

        return view('projects.index', [
            'projects' => Project::query()
                ->with('commissionType', 'schema')
                ->get()
                ->map(static function (Project $project) use ($selectionIDs) {
                    return [
                        'id' => $project->id,
                        'inSelection' => $selectionIDs->contains($project->id) ? 'Ja' : '',
                        'name' => $project->description,
                        'schema' => optional($project->schema)->name,
                        'type' => optional($project->commissionType)->name,
                        'status' => $project->wasApproved()
                            ? null
                            : '<div class="badge badge-warning">Ausstehend</div>',
                        'createdAt' => optional($project->created_at)->format('Y-m-d'),
                        'launchedAt' => optional($project->launched_at)->format('Y-m-d'),
                        'updatedAt' => optional($project->updated_at)->format('Y-m-d'),
                        'links' => [
                            'self' => route('projects.show', $project),
                        ],
                    ];
                }),
            'selection' => $selectionIDs,
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

        $project->loadCount('investments');

        if ($this->checkForError($project) !== null) {
            $project->in_iframe = false;
        }

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
            'pdp_link' => [
                'nullable',
                new GermanUrlRule(),
            ],
            'schema' => 'nullable|numeric|exists:schemas,id',
            'commissionType' => 'nullable|numeric|exists:commission_types,id',
        ]);

        if (array_key_exists('pdp_link', $data)) {
            $project->pdp_link = $data['pdp_link'];
            $project->save();

            flash_success('Der PDP Link wurde erfolgreich gespeichert.');
        }

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
