<?php

namespace App\Http\Controllers;

use App\Agb;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('create agbs');

        $list = Agb::with('users')->latest()->get();

        return view('agbs.index', compact('list', 'canDelete'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create agbs');

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create agbs');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agb  $agb
     * @return \Illuminate\Http\Response
     */
    public function show(Agb $agb)
    {
        $this->authorize('edit agbs');

        $agb->load('users');

        return view('agbs.show', compact('agb'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agb  $agb
     * @return \Illuminate\Http\Response
     */
    public function edit(Agb $agb)
    {
        $this->authorize('edit agbs');

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agb  $agb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agb $agb)
    {
        $this->authorize('edit agbs');

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agb $agb
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Agb $agb)
    {
        $this->authorize('delete agbs');

        abort_unless($agb->canBeDeleted(), Response::HTTP_LOCKED, 'Users may have already accepted these AGB');

        $agb->delete();

        return redirect()->route('agbs.index');
    }
}
