<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Commission;
use Illuminate\Database\Eloquent\Collection;

class CommissionRepository
{
    /**
     * Gets all commissions that are billable:
     * - Associated with a user account
     * - Not yet associated with a bill
     * - Reviewed by the team.
     *
     * @param array $columns
     * @return Collection
     */
    public function getBillable($columns = ['*']): Collection
    {
        return Commission::query()
            ->whereNotNull('reviewed_at')
            ->whereNull('bill_id')
            ->whereHas('user')
            ->select($columns)
            ->get();
    }
}
