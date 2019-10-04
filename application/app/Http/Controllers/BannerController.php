<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Banner;
use App\Models\BannerSet;
use App\Models\BannerLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Banner::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $sets = $user->company->bannerSets()
            ->with('banners', 'links')
            ->whereHas('banners')
            ->whereHas('links')
            ->get()
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
                    'urls' => collect($set->links)->map(function (BannerLink $link) use ($set, $user) {
                        return [
                            'key' => $link->title,
                            'value' => $link->getUrlForUser($user),
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
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
     * @param  Banner  $banner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->to('banners.sets.show', $banner->set);
    }
}
