<?php
declare(strict_types=1);


namespace App\Repositorys;

use App\Commission;
use Illuminate\Support\Facades\DB;

class CommissionRepository
{
    public function getNewestUpdatedAtDate()
    {
        return DB::table('projects')
            ->orderBy('updated_at', 'desc')
            ->get('updated_at');
    }
}
