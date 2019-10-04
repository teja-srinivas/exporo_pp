<?php

namespace App\Http\Controllers;

use App\Models\BannerSet;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class BannerSetController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BannerSet::class, 'set');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('affiliate.banners.sets.index', [
            'sets' => BannerSet::query()->withCount('banners')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('affiliate.banners.sets.create');
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
     */
    public function show(BannerSet $set)
    {
        return view('affiliate.banners.sets.show', [
            'set' => $set,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  BannerSet $set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BannerSet $set)
    {
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, BannerSet $set)
    {
        $data = $this->validate($request, [
            'title' => 'required|string',
            'urls.*.id' => 'numeric',
            'urls.*.key' => 'string',
            'urls.*.value' => 'url',
        ]);

        $set->update($data);

        // First, delete all links that are no longer in the list
        $set->links()->whereKeyNot(array_filter(Arr::pluck($data['urls'], 'id')))->delete();

        // Then create or update all other links
        foreach ($data['urls'] as $link) {
            $attributes = [
                'title' => $link['key'],
                'url' => $link['value'],
            ];

            if (isset($link['id'])) {
                $set->links()->updateOrCreate(['id' => $link['id']], $attributes);
            } else {
                $set->links()->create($attributes);
            }
        }

        flash_success();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BannerSet  $set
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(BannerSet $set)
    {
        if ($set->delete()) {
            flash_success('Erfolgreich gelöscht');
        }

        return redirect()->to('banners.sets.index');
    }
}
