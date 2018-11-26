<?php

namespace App\Traits;

trait Importable
{
    public static function getNewestUpdatedAtDate()
    {
        return self::query()->latest('updated_at')->value('updated_at') ?? 0;
    }
}
