<?php

namespace App\Http\Controllers\Api\Actions;

use App\Traits\Encryptable;
use Illuminate\Http\Request;

class DecryptController
{

    public function __invoke(Request $request)
    {
        return array_map(function ($value) {
            return Encryptable::decrypt($value);
        }, $request->all());
    }

}
