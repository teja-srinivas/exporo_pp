<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Link::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('affiliate.links.index', [
            'links' => Link::query()->with('userInstance')->orderBy('title')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('affiliate.links.create');
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
     * @return \Illuminate\View\View
     */
    public function edit(Link $link)
    {
        return view('affiliate.links.edit', [
            'link' => $link,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Link $link
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(Request $request, Link $link)
    {
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
        $link->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.links.index');
    }
}
