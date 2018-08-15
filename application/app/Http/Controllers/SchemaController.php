<?php

namespace App\Http\Controllers;

use App\Schema;
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

        return view('schemas.index', [
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
        return view('schemas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'formula' => 'required',
        ]);

        Schema::create($data);

        flash_success('Schema wurde angelegt');

        return redirect()->route('schemas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function show(Schema $schema)
    {
        $projects = $schema->projects()->orderBy('name')->get();

        return view('schemas.show', compact('schema', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function edit(Schema $schema)
    {
        return view('schemas.edit', compact('schema'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schema $schema)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'formula' => 'required',
        ]);

        $schema->fill($data)->save();

        flash_success('Schema wurde aktualisiert');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schema $schema
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Schema $schema)
    {
        $schema->delete();

        flash_success('Schema wurde gelöscht');

        return redirect()->route('schemas.index');
    }
}
