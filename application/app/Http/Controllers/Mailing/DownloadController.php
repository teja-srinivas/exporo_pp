<?php

namespace App\Http\Controllers\Mailing;

use App\Models\Mailing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Mailing::class, 'mail');
    }

    /**
     * Starts a download of the HTML-mail as seen by the currently logged in user.
     *
     * @param  Mailing  $mail
     * @param  Request  $request
     * @return StreamedResponse
     */
    public function show(Mailing $mail, Request $request): StreamedResponse
    {
        return response()->streamDownload(static function () use ($request, $mail) {
            new_relic_disable();
            echo $mail->getHtmlForUser($request->user());
        }, "{$mail->title}.html");
    }
}
