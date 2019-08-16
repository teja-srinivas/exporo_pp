<?php

namespace App\Http\Controllers;

use App\Models\Mailing;
use Illuminate\Http\Request;

class MailingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Mailing::class);

        return view('affiliate.mailings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Mailing::class);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => 'nullable|file'
        ]);

        $this->processUploadedHtmlFile($request, $data);

        Mailing::query()->create($data);

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mailing  $mail
     * @return \Illuminate\View\View
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
     * @param  \App\Models\Mailing $mail
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Mailing $mail
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(Request $request, Mailing $mail)
    {
        $this->authorize('update', $mail);

        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => 'nullable|file'
        ]);

        $this->processUploadedHtmlFile($request, $data);

        if ($mail->fill($data)->saveOrFail()) {
            flash_success();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mailing $mail
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Mailing $mail)
    {
        $this->authorize('delete', $mail);

        $mail->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Show the HTML-content of the mailing
     *
     * @param  \App\Models\Mailing $mail
     * @return HTML
     */
    public function htmlPreview(Mailing $mail)
    {
        return $mail->getHtmlForUser(auth()->user());
    }

    /**
     * Starts a download of the HTML-mail
     *
     * @param  \App\Models\Mailing $mail
     * @return File
     */
    public function download(Mailing $mail)
    {
        header("Content-type: text/html");
        header("Content-Disposition: attachment; filename=".$mail->title.".html");
        return $mail->getHtmlForUser(auth()->user());
    }

    /**
     * Check if the user uploaded a html file and assigns it to the $data
     *
     * @param  Illuminate\Http\Request $request
     * @param  Array $request
     * @throws \Exception
     */
    private function processUploadedHtmlFile($request, &$data) {
      // Did the user upload a HTML-file?
      // If yes: Read content of the file and store to 'html'-column
      if( $request->file('file') ) {
        $data['html'] = $request->file('file')->get();
      }
    }
}
