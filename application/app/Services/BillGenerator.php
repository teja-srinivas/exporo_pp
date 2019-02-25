<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Commission;
use App\Repositories\BillRepository;
use App\Repositories\CommissionRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BillGenerator
{
    /** @var BillRepository */
    protected $bills;

    /** @var CommissionRepository */
    protected $commissions;

    /**
     * @param BillRepository $bills
     * @param CommissionRepository $commissions
     */
    public function __construct(BillRepository $bills, CommissionRepository $commissions)
    {
        $this->bills = $bills;
        $this->commissions = $commissions;
    }

    /**
     * Generates bills for all billable commissions
     * and sets their release date to the one given.
     *
     * @param Carbon $releaseAt The date the bills should be presented to the user
     * @return Collection The list of bills that have been generated
     */
    public function generate(Carbon $releaseAt): Collection
    {
        return $this->commissions->getBillable(['user_id', 'id', 'net'])
            ->load('user.details', 'user.permissions')
            ->filter(function (Commission $commission) {
                // Pre-select all valid commissions
                return $commission->user->canBeBilled();
            })
            ->groupBy('user_id')
            ->map(function (Collection $commissions) use ($releaseAt) {
                // Create bills for each user and assign it to their commissions
                return $this->bills->create(
                    $commissions->first()->user,
                    $releaseAt,
                    $commissions
                );
            });
    }
}
