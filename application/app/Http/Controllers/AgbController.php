<?php

namespace App\Http\Controllers;

use App\Models\Agb;
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
        $this->authorize('viewAny', Agb::class);

        $list = Agb::query()
            ->join('agb_user', 'agb_user.agb_id', 'agbs.id')
            ->latest()
            ->groupBy('agbs.id')
            ->selectRaw('count(user_id) as users')
            ->selectRaw('agbs.*')
            ->get();

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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', Agb::class);
        $data = $request->validate([
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'effective_from' => 'required|date',
            'file' => 'required|mimes:pdf',
        ]);

        // Replace the old file, if exists
        $agb = new Agb($data);
        $agb->filename = $request['type'];
        Storage::disk('s3')->put(Agb::DIRECTORY . '/' . $request['name'], $request->file('file')->get());
        $agb->is_default = $request->has('is_default');

        $agb->saveOrFail();

        flash_success('AGB wurden angelegt');

        return redirect()->route('agbs.edit', $agb);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agb $agb
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
     * @param  \App\Models\Agb $agb
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
     * @param  \App\Models\Agb $agb
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, Agb $agb)
    {
        $this->authorize('update', $agb);

        $data = $request->validate([
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'effective_from' => 'required|date',
            'file' => 'nullable|mimes:pdf',

        ]);

        // Replace the old file, if exists
        if ($request->has('file')) {
            $oldFilename = $agb->filename;
            $filename = $request['name'];
            Storage::disk('s3')->put(Agb::DIRECTORY . '/' . $filename, $request->file('file')->get());

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
     * @param  \App\Models\Agb $agb
     * @return \Illuminate\Http\RedirectResponse
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function download(Agb $agb)
    {
        abort_unless(Storage::disk('s3')->exists(Agb::DIRECTORY . '/' . $agb->name), Response::HTTP_NOT_FOUND);

        $file = Storage::disk('s3')->get(Agb::DIRECTORY . '/' . $agb->name);

        return response($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename=' . $agb->getReadableFilename(),
            'filename' => $agb->getReadableFilename(),
        ]);
    }

    /**
     * Finds and redirects to the latest AGB of the given type.
     *
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function latest(string $type)
    {
        $agb = Agb::current($type);

        abort_if($agb === null, 404);

        return redirect($agb->getDownloadUrl());
    }
}
