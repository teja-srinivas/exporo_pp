<?php

namespace App\Http\Controllers\Mailing;

use App\Models\Mailing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class PreviewController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Mailing::class);
    }

    /**
     * Shows the HTML-content of the mailing as seen by the currently logged in user.
     *
     * @param  Mailing  $mail
     * @param  Request  $request
     * @return Response
     */
    public function show(Mailing $mail, Request $request): Response
    {
        new_relic_disable();

        return response()->make($mail->getHtmlForUser($request->user()));
    }
}
