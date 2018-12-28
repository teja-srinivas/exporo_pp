<?php

namespace App\Http\Controllers;

use App\Models\Mailing;
use Illuminate\Http\Request;

class MailingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('affiliate.mailings.index', [
            'mailings' => Mailing::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
        ]);

        Mailing::query()->create($data);

        return redirect()->route('affiliate.mails.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mailing  $mail
     * @return \Illuminate\Http\Response
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
     * @param  \App\Models\Mailing  $mail
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
        ]);

        if ($mail->fill($data)->saveOrFail()) {
            flash_success();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mailing $mail
     * @return void
     * @throws \Exception
     */
    public function destroy(Mailing $mail)
    {
        $this->authorize('delete', $mail);

        $mail->delete();

        flash_success('Eintrag wurde gelöscht');

        return redirect()->route('affiliate.mails.index');
    }
}
