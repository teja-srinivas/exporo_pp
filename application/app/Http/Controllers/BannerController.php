<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
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

        return back();
    }
}
