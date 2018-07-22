<?php

namespace App\Traits;

trait Importable
{
    public static function getNewestUpdatedAtDate()
    {
        return optional(self::query()->latest('updated_at')->first())->updated_at ?? 0;
    }
}
