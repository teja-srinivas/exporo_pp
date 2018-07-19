<?php
declare(strict_types=1);


namespace App\Repositorys;

use Illuminate\Support\Facades\DB;

class CommissionRepository
{
    public function getNewestUpdatedAtDate()
    {
        return DB::table('projects')
            ->orderBy('updated_at', 'desc')
            ->first(['updated_at']);
    }
}
