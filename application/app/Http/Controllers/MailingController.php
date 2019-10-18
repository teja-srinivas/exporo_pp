<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Mailing;
use Illuminate\View\View;
use App\Helper\TagReplacer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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

        /** @var Mailing $mail */
        $mail = Mailing::query()->create($data);
        $this->fillMissingVariables($request->user(), $mail);

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
            'variables.*.url' => 'url',
        ]);

        $this->addUploadedFileToInput($request, $data);

        $mail->fill($data);
        $this->fillMissingVariables($request->user(), $mail);

        if ($mail->saveOrFail()) {
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

    private function fillMissingVariables(User $user, Mailing $mail): void
    {
        $variables = $mail->variables ?? [];

        $tags = TagReplacer::findTags($mail->html);
        $existing = array_merge(
            array_keys(TagReplacer::getUserTags($user)),
            array_column($variables, 'placeholder')
        );

        foreach (array_diff($tags, $existing) as $tag) {
            $variables[] = [
                'placeholder' => $tag,
                'type' => 'link',
                'url' => '',
            ];
        }

        $mail->variables = $variables;
    }
}
