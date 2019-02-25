<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Commission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BillRepository
{

    /**
     * Creates a bill for the the given user and commissions
     * which will be released at the given date.
     *
     * @param User $user
     * @param Carbon $releaseAt
     * @param Collection $commissions
     * @return Bill
     */
    public function create(User $user, Carbon $releaseAt, Collection $commissions): Bill
    {
        /** @var Bill $bill */
        $bill = Bill::query()->forceCreate([
            'user_id' => $user->getKey(),
            'released_at' => $releaseAt,
        ]);

        Commission::query()->whereKey($commissions->pluck('id'))->update([
            'bill_id' => $bill->getKey(),
        ]);

        return $bill;
    }

}
