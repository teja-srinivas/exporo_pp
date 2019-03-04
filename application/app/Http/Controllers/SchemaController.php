<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Schema;
use App\Rules\Formula;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('list', Schema::class);

        return response()->view('schemas.index', [
            'schemas' => Schema::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('schemas.create');
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
            'formula' => ['required', new Formula],
        ]);

        Schema::query()->create($data);

        flash_success('Schema wurde angelegt');

        return redirect()->route('schemas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function show(Schema $schema)
    {
        $projects = $schema->projects()->get()->map(function (Project $project) {
            return [
                'id' => $project->id,
                'project' => $project->description,
                'createdAt' => optional($project->created_at)->format('Y-m-d'),
                'links' => [
                    'self' => route('projects.show', $project),
                ],
            ];
        });

        return response()->view('schemas.show', compact('schema', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function edit(Schema $schema)
    {
        return response()->view('schemas.edit', compact('schema'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Schema $schema
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Schema $schema)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'formula' => ['required', new Formula],
        ]);

        $schema->fill($data)->save();

        flash_success('Schema wurde aktualisiert');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schema $schema
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Schema $schema)
    {
        $schema->delete();

        flash_success('Schema wurde gelöscht');

        return redirect()->route('schemas.index');
    }
}
