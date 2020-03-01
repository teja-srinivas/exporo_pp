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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

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

//TODO add      disk($campaign->disk)->
        if (isset($data['image'])) {
            $campaign->image_url = Storage::put($campaign->getImagePath(), $image, 'public');
            $campaign->image_name = $image->getClientOriginalName();
        }

//TODO add      disk($campaign->disk)->
        if (isset($data['document'])) {
            $campaign->document_url = Storage::put($campaign->getDocumentPath(), $document, 'public');
            $campaign->document_name = $document->getClientOriginalName();
        }

        $campaign->save();
        $campaign->users()->sync(json_decode($data['selection']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
//TODO add      disk($campaign->disk)->
            Storage::delete($file);
//TODO add      disk($campaign->disk)->
            $campaign->image_url = Storage::put($campaign->getImagePath(), $image, 'public');
            $campaign->image_name = $image->getClientOriginalName();
        }

        if (isset($data['document'])) {
            $file = $campaign->getDocumentDownloadUrl();
 //TODO add      disk($campaign->disk)->
            Storage::delete($file);
//TODO add      disk($campaign->disk)->
            $campaign->document_url = Storage::put($campaign->getDocumentPath(), $document, 'public');
            $campaign->document_name = $document->getClientOriginalName();
        }

        $campaign->users()->sync(json_decode($data['selection']));
        $campaign->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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

//TODO add      disk($campaign->disk)->
        Storage::delete($file);
        $campaign->save();
    }
}
