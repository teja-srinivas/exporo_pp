<?php

namespace App\Http\Controllers;

use App\Models\CommissionType;
use Illuminate\Http\Request;

class CommissionTypeController extends Controller
{


    public function index()
    {
        $this->authorize('list', CommissionType::class);

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
        return view('commissions.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $data['is_project_type'] = $request->has('is_project_type');

        CommissionType::create($data);

        flash_success('Provisionstyp wurde angelegt');

        return redirect()->route('commissions.types.index');
    }

    /**
     * @param CommissionType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CommissionType $type)
    {
        $projects = $type->is_project_type
            ? $type->projects()->orderBy('name')->get()
            : [];

        return view('commissions.types.show', compact('type', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CommissionType $type
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionType $type)
    {
        return view('commissions.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CommissionType $type
     * @return \Illuminate\Http\Response
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
