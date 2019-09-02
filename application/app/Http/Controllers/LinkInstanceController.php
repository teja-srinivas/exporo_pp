<?php

namespace App\Http\Controllers;

use App\LinkInstance;
use Illuminate\Http\Request;

class LinkInstanceController
{
    public function show(Request $request, LinkInstance $link)
    {
        $link->createClick($request);

        return response()->redirectTo($link->buildRealUrl());
    }
}
