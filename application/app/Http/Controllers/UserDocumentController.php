<?php

namespace App\Http\Controllers;

use App\Agb;
use App\Bill;
use App\Document;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // TODO maybe extract this to a different route?
        if ($user->cannot('list', Document::class)) {
            $documents =
                ($user->documents->map(function (Document $document) {
                    return [
                        'type' => 'Dokument',
                        'title' => $document->name,
                        'link' => $document->getDownloadUrl(),
                        'created_at' => $document->created_at,
                    ];
                }))
                ->merge($user->agbs()->latest()->get()->map(function (Agb $agb) {
                    return [
                        'type' => __('AGB'),
                        'title' => $agb->name,
                        'link' => $agb->getDownloadUrl(),
                        'created_at' => $agb->pivot->created_at,
                    ];
                }))
                ->sortByDesc('created_at');

            return response()->view('users.documents', compact('documents'));
        }

        $this->authorize('list', Document::class);

        $documents = Document::orderBy('name')->get();

        return response()->view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', Document::class);

        $user = User::find($request->get('user_id'));

        $users = User::query()
            ->get(['id', 'first_name', 'last_name'])
            ->sortBy('last_name', SORT_NATURAL | SORT_FLAG_CASE)
            ->mapWithKeys(function (User $user) {
                return [$user->id => $user->last_name . ', ' . $user->first_name];
            });

        return response()->view('documents.create', compact('user', 'users'));
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
        $this->authorize('create', Document::class);

        $request->validate([
            'user' => 'required|exists:users,id',
            'name' => 'required|string',
            'file' => 'required|mimes:pdf',
            'description' => 'nullable|string',
        ]);

        $document = new Document($request->only('name', 'description'));
        $document->user()->associate($request->get('user'));
        $document->filename = $request->file('file')->store(Document::DIRECTORY);
        $document->saveOrFail();

        flash_success('Dokument wurde angelegt');

        return redirect()->route('documents.show', [$document, 'user' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return response()->view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        return response()->view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $data = $request->validate([
            'name' => 'required|string',
            'file' => 'nullable|mimes:pdf',
            'description' => 'nullable|string',
        ]);

        $document->fill(array_only($data, ['name', 'description']));

        // Replace the old file, if exists
        if ($request->has('file')) {
            $oldFilename = $document->filename;
            $document->filename = $request->file('file')->store(Document::DIRECTORY);

            if (!empty($oldFilename)) {
                Storage::delete($oldFilename);
            }
        }

        $document->saveOrFail();

        flash_success();

        return redirect()->route('documents.edit', $document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        //
    }

    public function download(Document $document)
    {
        abort_unless(Storage::exists($document->filename), Response::HTTP_NOT_FOUND);

        return Storage::download($document->filename, $document->getReadableFilename());
    }
}
