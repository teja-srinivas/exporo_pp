<?php

namespace App\Http\Controllers;

use App\CommissionType;
use Illuminate\Http\Request;


class CommissionTypeController extends Controller
{


    public function index()
    {
        $this->authorize('list', CommissionType::class);

        return view('commission_types.index', [
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
        return view('commission_types.create');
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

        CommissionType::create($data);

        flash_success('Provisionstyp wurde angelegt');

        return redirect()->route('commissionTypes.index');
    }

    /**
     * @param CommissionType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CommissionType $type)
    {
        $projects = $type->projects()->orderBy('name')->get();

        return view('commission_types.show', compact('type', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CommissionType $type
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionType $type)
    {
        return view('commission_types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CommissionType $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissionType $type)
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

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
