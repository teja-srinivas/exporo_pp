<?php

namespace App\Http\Controllers\Api\Actions;

use App\Traits\Encryptable;
use Illuminate\Http\Request;

class EncryptController
{
    public function __invoke(Request $request)
    {
        return array_map(function ($value) {
            return encrypt($value);
        }, $request->all());
    }
}
