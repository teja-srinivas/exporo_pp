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
use App\Helper\TagReplacer;

class MailingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Mailing::class, 'mail');
    }

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
        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => ['nullable', 'file'],
            'variables.*.placeholder' => 'string',
            'variables.*.type' => ['string', 'in:link'],
            'variables.*.url' => 'string' // can not use url because of placeholders in URL
        ]);

        $this->addUploadedFileToInput($request, $data);

        $mail = Mailing::create($data);

        $this->createNewVariables($mail);

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
     */
    public function edit(Mailing $mail)
    {
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
        $data = $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'text' => 'required',
            'file' => ['nullable', 'file'],
            'variables.*.placeholder' => 'string',
            'variables.*.type' => ['string', 'in:link'],
            'variables.*.url' => 'string' // can not use url because of placeholders in URL
        ]);

        $this->addUploadedFileToInput($request, $data);

        if ($mail->fill($data)->saveOrFail()) {
            flash_success();
        }

        $this->createNewVariables($mail);

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
        $mail->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Check if the user uploaded a html file and assigns it to the input data.
     *
     * @param  Request $request
     * @param  array $input
     * @throws FileNotFoundException
     */
    private function addUploadedFileToInput(Request $request, array &$input)
    {
        if ($request->file('file')) {
            $input['html'] = $request->file('file')->get();
        }
    }

    private function createNewVariables($mail) {
      $variables = $mail->variables ? $mail->variables : [];

      foreach ( TagReplacer::findTags($mail->html) as $tag) {
        if( $this->shouldAddTag($tag, $variables)) {
          $variables[] = [
            'placeholder' => $tag,
            'type' => 'link',
            'url' => '',
          ];
        }
      }

      $mail->variables = $variables;
      $mail->saveOrFail();
    }

    private function shouldAddTag(string $tag, array $existingVariables = []): bool
    {
      // User-Tag?
      if( in_array($tag, TagReplacer::availableUserTags() )) return false;

      // Already added?
      foreach ($existingVariables as $variable) {
        if( $variable['placeholder'] == $tag ) return false;
      }

      return true;
    }
}
