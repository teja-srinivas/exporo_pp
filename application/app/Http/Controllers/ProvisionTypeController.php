<?php

namespace App\Http\Controllers;

use App\ProvisionType;
use Illuminate\Http\Request;


class ProvisionTypeController extends Controller
{


    public function index()
    {
        $this->authorize('list', ProvisionType::class);

        return view('provision_types.index', [
            'provisionType' => ProvisionType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('provision_types.create');
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
        ]);

        ProvisionType::create($data);

        flash_success('Provisionstyp wurde angelegt');

        return redirect()->route('provisionTypes.index');
    }

    /**
     * @param ProvisionType $provisionType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ProvisionType $provisionType)
    {
        $projects = $provisionType->projects()->orderBy('name')->get();

        return view('provision_types.show', compact('provisionType', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProvisionType $provisionType)
    {
        return view('provision_types.edit', compact('provisionType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProvisionType $provisionType)
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $provisionType->fill($data)->save();

        flash_success('Provisionstyp wurde aktualisiert');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
