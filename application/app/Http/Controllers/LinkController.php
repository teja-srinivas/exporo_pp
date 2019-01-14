<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('affiliate.links.index', [
            'links' => Link::query()->orderBy('title')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Link::class);

        return view('affiliate.links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Link::class);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'url' => 'required',
        ]);

        Link::query()->create($data);

        return redirect()->route('affiliate.links.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Link $link
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Link $link)
    {
        $this->authorize('update', $link);

        return view('affiliate.links.edit', [
            'link' => $link,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Link $link
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(Request $request, Link $link)
    {
        $this->authorize('update', $link);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'url' => 'required',
        ]);

        if ($link->fill($data)->saveOrFail()) {
            flash_success();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Link $link
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Link $link)
    {
        $this->authorize('delete', $link);

        $link->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.links.index');
    }
}
