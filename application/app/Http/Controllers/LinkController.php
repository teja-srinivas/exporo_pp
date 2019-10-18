<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\LinkClick;
use App\Models\Link;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Link::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('affiliate.links.index', [
            'links' => Link::query()
                ->visibleForUser($request->user())
                ->with(['userInstance' => static function (MorphOne $related) {
                    // Prevents eager loading the $with relationship
                    // (since we already do this using the parent query)
                    // https://github.com/laravel/framework/issues/30007
                    $related->without('link');
                }])
                ->orderBy('title')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('affiliate.links.create', [
            'shortLinkPartners' => $this->getShortLinkPartners(),
        ]);
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
            'user' => ['nullable', 'array'],
        ]);

        /** @var Link $link */
        $link = Link::query()->create($data);

        $link->users()->sync(array_keys($data['user'] ?? []));

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
            'shortLinkPartners' => $this->getShortLinkPartners(),
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
            'user' => ['nullable', 'array'],
        ]);

        $link->users()->sync(array_keys($data['user'] ?? []));

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
        DB::transaction(static function () use ($link) {
            LinkClick::query()
                ->whereIn('instance_id', $link->instances()->toBase()->select('id'))
                ->delete();

            $link->instances()->delete();
            $link->users()->detach();
            $link->delete();
        });

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.links.index');
    }

    protected function getShortLinkPartners(): Collection
    {
        return UserDetails::query()
            ->whereIn('id', User::query()
                ->withPermission('features.link-shortener.links')
                ->select('id'))
            ->pluck('display_name', 'id');
    }
}
