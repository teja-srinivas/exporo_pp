<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Actions;

use App\Traits\Encryptable;
use Illuminate\Http\Request;

class DecryptController
{
    public function __invoke(Request $request)
    {
        return array_map(static function ($value) {
            return Encryptable::decrypt($value);
        }, $request->all());
    }
}
