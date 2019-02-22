<?php

namespace App\Http\Controllers;

use App\Models\BannerSet;
use Illuminate\Http\Request;

class BannerSetController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('list', BannerSet::class);

        return view('affiliate.banners.sets.index', [
            'sets' => BannerSet::query()->withCount('banners')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BannerSet::class);

        return view('affiliate.banners.sets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', BannerSet::class);

        $data = $this->validate($request, [
            'title' => 'required|string',
            'urls.*.key' => 'string',
            'urls.*.value' => 'url',
        ]);

        $set = new BannerSet($data);
        $set->company()->associate($request->user()->company_id);
        $set->save();

        return redirect()->route('banners.sets.show', $set);
    }

    /**
     * Display the specified resource.
     *
     * @param  BannerSet $set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(BannerSet $set)
    {
        $this->authorize('view', $set);

        return view('affiliate.banners.sets.show', [
            'set' => $set,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  BannerSet $set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(BannerSet $set)
    {
        $this->authorize('update', $set);

        return view('affiliate.banners.sets.edit', [
            'set' => $set,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BannerSet $set
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, BannerSet $set)
    {
        $this->authorize('update', $set);

        $data = $this->validate($request, [
            'title' => 'required|string',
            'urls.*.key' => 'string',
            'urls.*.value' => 'url',
        ]);

        $set->update($data);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BannerSet $set
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(BannerSet $set)
    {
        $this->authorize('delete', $set);

        if ($set->delete()) {
            flash_success('Erfolgreich gelöscht');
        }

        return redirect()->to('banners.sets.index');
    }
}
