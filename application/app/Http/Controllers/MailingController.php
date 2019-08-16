<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Mailing;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class MailingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('affiliate.mailings.index', [
            'mailings' => Mailing::query()->orderBy('title')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $this->authorizeResource(Mailing::class);

        return view('affiliate.mailings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws FileNotFoundException
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->authorizeResource(Mailing::class);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => ['nullable', 'file'],
        ]);

        $this->addUploadedFileToInput($request, $data);

        Mailing::query()->create($data);

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Mailing  $mail
     * @return View
     */
    public function show(Mailing $mail)
    {
        return view('affiliate.mailings.show', [
            'mailing' => $mail,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Mailing  $mail
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Mailing $mail)
    {
        $this->authorize('update', $mail);

        return view('affiliate.mailings.edit', [
            'mailing' => $mail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Mailing  $mail
     * @return RedirectResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Request $request, Mailing $mail)
    {
        $this->authorizeResource($mail);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => ['nullable', 'file'],
        ]);

        $this->addUploadedFileToInput($request, $data);

        if ($mail->fill($data)->saveOrFail()) {
            flash_success();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Mailing  $mail
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Mailing $mail)
    {
        $this->authorizeResource($mail);

        $mail->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Check if the user uploaded a html file and assigns it to the input data
     *
     * @param  Request $request
     * @param  array $input
     * @throws FileNotFoundException
     */
    private function addUploadedFileToInput(Request $request, array &$input) {
        if ($request->file('file')) {
            $input['html'] = $request->file('file')->get();
        }
    }
}
