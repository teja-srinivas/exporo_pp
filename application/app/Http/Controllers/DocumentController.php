<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('list', Document::class);

        $documents = Document::query()->orderBy('name')->get();

        return response()->view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', Document::class);

        $users = User::query()
            ->with('details')
            ->orderBy('id')
            ->get(['id']);

        $user = $users->get($request->get('user_id'));

        $users = $users->mapWithKeys(function (User $user) {
            return [$user->getKey() => $user->getKey() . ' - ' . $user->details->display_name];
        });

        return response()->view('documents.create', compact('user', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', Document::class);

        $this->validate($request, [
            'user' => 'required|exists:users,id',
            'name' => 'required|string',
            'file' => 'required|mimes:pdf',
            'description' => 'nullable|string',
        ]);

        $document = new Document($request->only('name', 'description'));
        $document->user()->associate($request->get('user'));
        $document->filename = Storage::disk($document->disk)->put(Document::DIRECTORY, $request->file('file'));
        $document->saveOrFail();

        flash_success('Dokument wurde angelegt');

        return redirect()->route('documents.show', [$document, 'user' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return response()->view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Document $document
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        return response()->view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Document $document
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $data = $this->validate($request, [
            'name' => 'required|string',
            'file' => 'nullable|mimes:pdf',
            'description' => 'nullable|string',
        ]);

        $document->fill(Arr::only($data, ['name', 'description']));

        // Replace the old file, if exists
        if ($request->has('file')) {
            $oldFilename = $document->filename;

            $disk = Storage::disk('s3');
            $document->filename = $disk->put(Document::DIRECTORY, $request->file('file'));

            if (!empty($oldFilename)) {
                $disk->delete($oldFilename);
            }
        }

        $document->saveOrFail();

        flash_success();

        return redirect()->route('documents.edit', $document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return redirect()->route('documents.index');
    }
}
