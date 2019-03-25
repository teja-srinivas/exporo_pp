<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Commission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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

    public function withoutPdf(): EloquentCollection
    {
        return Bill::query()->whereNull('pdf_created_at')->get();
    }

    /**
     * Returns a simplified bill model that contains
     * - the id,
     * - the total sum of the bill
     * - the amount of commissions contained in the bill
     *
     * Optionally, a specific user can be provided.
     *
     * @param int|null $forUser The user ID
     * @param callable|null $modifier
     * @return EloquentCollection
     */
    public function getDetails(?int $forUser = null, ?callable $modifier = null): EloquentCollection
    {
        return Bill::query()
            ->join('commissions', 'commissions.bill_id', 'bills.id')
            ->groupBy('bills.id')
            ->select('bills.id', 'bills.user_id', 'bills.created_at', 'bills.released_at')
            ->selectRaw('COUNT(commissions.id) as commissions')
            ->selectRaw('SUM(commissions.gross) as gross')
            ->selectRaw('SUM(commissions.net) as net')
            ->when($forUser !== null, function (Builder $query) use ($forUser) {
                $query->where('bills.user_id', $forUser);
            })
            ->when($modifier !== null, $modifier)
            ->get();
    }

    public function unsent($query = null): EloquentCollection
    {
        return Bill::query()
            ->whereDate('released_at', '<=', now())
            ->whereNotNull('pdf_created_at')
            ->whereNull('mail_sent_at')
            ->when($query !== null, $query)
            ->get();
    }

    public function unsentWithTotals(): EloquentCollection
    {
        return $this->unsent(function (Builder $query) {
            $query
                ->join('commissions', 'bills.id', 'commissions.bill_id')
                ->groupBy('bills.id')
                ->select('bills.*')
                ->selectRaw('sum(net) as net');
        });
    }
}
