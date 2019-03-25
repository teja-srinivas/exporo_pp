<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerSet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Banner::class);

        /** @var User $user */
        $user = $request->user();
        $sets = $user->company->bannerSets()
            ->with('banners')
            ->get()
            ->reject(function (BannerSet $set) {
                return empty($set->urls) || $set->banners->isEmpty();
            })
            ->map(function (BannerSet $set) use ($user) {
                return [
                    'name' => $set->title,
                    'banners' => $set->banners->map(function (Banner $banner) {
                        return [
                            'height' => $banner->height,
                            'width' => $banner->width,
                            'url' => $banner->getDownloadUrl(),
                        ];
                    }),
                    'urls' => collect($set->urls)->map(function (array $url) use ($set, $user) {
                        return [
                            'key' => $url['key'],
                            'value' => $set->getUrlForUser($url['value'], $user),
                        ];
                    }),
                ];
            })->values();

        return view('affiliate.banners.index', [
            'sets' => $sets,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Banner::class);

        $data = $this->validate($request, [
            'set_id' => 'exists:banner_sets,id',
            'file' => 'mimes:jpeg,gif,png',
        ]);

        /** @var BannerSet $set */
        $set = BannerSet::query()->findOrFail($data['set_id']);

        // Fetch image dimensions
        $image = $request->file('file');
        [$width, $height] = @getimagesize($image->getRealPath());

        /** @var Banner $banner */
        $banner = $set->banners()->newModelInstance([
            'width' => $width,
            'height' => $height,
        ]);

        $banner->set()->associate($set);
        $banner->filename = Storage::disk($banner->disk)->put($banner->getStoragePath(), $image, 'public');
        $banner->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        $this->authorize('delete', $banner);

        $banner->delete();

        return redirect()->to('banners.sets.show', $banner->set);
    }
}
