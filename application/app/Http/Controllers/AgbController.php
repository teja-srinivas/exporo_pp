<?php

namespace App\Http\Controllers;

use App\Agb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
        $this->authorize('list', Agb::class);

        $list = Agb::with('users')->latest()->get();

        return response()->view('agbs.index', compact('list', 'canDelete'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Agb::class);

        return response()->view('agbs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', Agb::class);

        $data = $request->validate([
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'file' => 'required|mimes:pdf',
        ]);

        // Replace the old file, if exists
        $agb = new Agb($data);
        $agb->filename = $request->file('file')->store(Agb::DIRECTORY);
        $agb->is_default = $request->has('is_default');

        $agb->saveOrFail();

        flash_success('AGB wurden angelegt');

        return redirect()->route('agbs.edit', $agb);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agb $agb
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Agb $agb)
    {
        $this->authorize('view', $agb);

        $agb->load('users');

        return response()->view('agbs.show', compact('agb'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agb $agb
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Agb $agb)
    {
        $this->authorize('update', $agb);

        return response()->view('agbs.edit', compact('agb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Agb $agb
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, Agb $agb)
    {
        $this->authorize('update', $agb);

        $data = $request->validate([
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'file' => 'nullable|mimes:pdf',
        ]);

        // Replace the old file, if exists
        if ($request->has('file')) {
            $oldFilename = $agb->filename;
            $agb->filename = $request->file('file')->store(Agb::DIRECTORY);

            if (!empty($oldFilename)) {
                Storage::delete($oldFilename);
            }
        }

        $agb->fill($data);
        $agb->is_default = $request->has('is_default');

        $agb->saveOrFail();

        flash_success();

        return redirect()->route('agbs.edit', $agb);
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
        $this->authorize('delete', $agb);

        abort_unless($agb->canBeDeleted(), Response::HTTP_LOCKED, 'Users may have already accepted these AGB');

        $agb->delete();
        Storage::delete($agb->filename);

        return redirect()->route('agbs.index');
    }

    /**
     * Reponds with a file download for the given AGB.
     * Errors if the file does not exist.
     *
     * @param Agb $agb
     * @return mixed
     */
    public function download(Agb $agb)
    {
        abort_unless(Storage::exists($agb->filename), Response::HTTP_NOT_FOUND);

        return Storage::download($agb->filename, $agb->getReadableFilename());
    }
}
