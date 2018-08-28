<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProvisionType;
use App\Schema;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $schemas = Schema::all()->pluck('name', 'id');
        $provisionTypes = ProvisionType::all()->pluck('name', 'id');

        return view('projects.show', compact('project', 'schemas', 'provisionTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $data = $this->validate($request, [
            'accept' => 'nullable|boolean',
            'schema' => 'nullable|numeric|exists:schemas,id',
            'provisionType' => 'nullable|numeric|exists:provision_types,id'
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

        if (isset($data['provisionType'])) {
            $project->provisionType()->associate(ProvisionType::findOrFail($data['provisionType']));
            $project->save();

            flash_success('Der ProvisionType wurde erfolgreich geändert.');
        }

        return back();
    }
}
