<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
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
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign $campaign
     * @return \Illuminate\View\View
     */
    public function edit(Campaign $campaign)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(Request $request, Campaign $campaign)
    {
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Campaign $campaign)
    {
        

        return redirect()->route('campaigns.index');
    }
}
