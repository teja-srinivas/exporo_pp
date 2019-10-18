<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Actions;

use Illuminate\Http\Request;

class EncryptController
{
    public function __invoke(Request $request)
    {
        return array_map(static function ($value) {
            return encrypt($value);
        }, $request->all());
    }
}
