<?php
declare(strict_types=1);


namespace App\Repositorys;

use App\Investment;
use Illuminate\Database\Eloquent\Collection;

final class InvestmentRepository
{
    public function getInvestmentsForInvestorNotCancelled($investorID)
    {
        Investment::all()
            ->where('investor_id', $investorID)
            ->where('cancelled_at', 'NULL');
    }

    public function getInvestmentsWithoutCommission(): Collection
    {
       return Investment::doesntHave('commissions')->get();
    }
}
