<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Document::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $documents = Document::query()
            ->with('user.details')
            ->orderBy('name')
            ->get();

        return response()->view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $userId = $request->get('user_id');

        if ($userId !== null) {
            $user = User::query()->findOrFail($userId);
        } else {
            $users = User::query()
                ->with('details')
                ->orderBy('id')
                ->get(['id']);

            $users = $users->mapWithKeys(function (User $user) {
                return [$user->getKey() => $user->getKey().' - '.$user->details->display_name];
            });
        }

        return response()->view('documents.create', compact('user', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
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

        return redirect()->route('documents.show', $document);
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     * @return Response
     */
    public function show(Document $document)
    {
        return response()->view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Document $document
     * @return Response
     */
    public function edit(Document $document)
    {
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

            if (! empty($oldFilename)) {
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
     * @throws Exception
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index');
    }
}
