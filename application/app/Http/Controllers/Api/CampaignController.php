<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCampaign;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCampaign  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampaign $request)
    {
        $data = $request->validated();

        $campaign = new Campaign($data);
        $image = $request->file('image');
        $document = $request->file('document');

        if (isset($data['image'])) {
            $campaign->image_url = Storage::disk($campaign->disk)->put($campaign->getImagePath(), $image, 'public');
            $campaign->image_name = $image->getClientOriginalName();
        }

        if (isset($data['document'])) {
            $campaign->document_url = Storage::disk($campaign->disk)
                ->put($campaign->getDocumentPath(), $document, 'public');
            $campaign->document_name = $document->getClientOriginalName();
        }

        $campaign->save();
        $campaign->users()->sync(json_decode($data['selection']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreCampaign  $request
     * @param  Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Campaign $campaign, StoreCampaign $request)
    {
        $data = $request->validated();

        $campaign->update($data);
        $image = $request->file('image');
        $document = $request->file('document');

        if (isset($data['image'])) {
            $file = $campaign->getImageDownloadUrl();
            Storage::disk($campaign->disk)->delete($file);
            $campaign->image_url = Storage::disk($campaign->disk)
                ->put($campaign->getImagePath(), $image, 'public');
            $campaign->image_name = $image->getClientOriginalName();
        }

        if (isset($data['document'])) {
            $file = $campaign->getDocumentDownloadUrl();
            Storage::disk($campaign->disk)->delete($file);
            $campaign->document_url = Storage::disk($campaign->disk)
                ->put($campaign->getDocumentPath(), $document, 'public');
            $campaign->document_name = $document->getClientOriginalName();
        }

        $campaign->users()->sync(json_decode($data['selection']));
        $campaign->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteFile(Request $request)
    {
        $type = $request->type;
        $campaign = Campaign::find($request->campaign);
        $file = null;

        if ($type === 'image') {
            $file = $campaign->getImageDownloadUrl();
            $campaign->image_url = null;
            $campaign->image_name = null;
        } elseif ($type === 'document') {
            $file = $campaign->getDocumentDownloadUrl();
            $campaign->document_url = null;
            $campaign->document_name = null;
        }

        Storage::disk($campaign->disk)->delete($file);
        $campaign->save();
    }
}
