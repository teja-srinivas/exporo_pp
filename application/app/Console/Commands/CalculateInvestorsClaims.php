<?php

namespace App\Console\Commands;

use App\Models\Investor;
use Illuminate\Console\Command;

class CalculateInvestorsClaims extends Command
{
    protected $signature = 'calculate:claim-end';

    protected $description = 'Calculates missing claim_end dates for investors';

    public function handle()
    {
        Investor::query()
            ->whereNull('claim_end')
            ->whereHas('user.contract')
            ->with('user.contract')
            ->each(function (Investor $investor) {
                $this->line("Calculating claim for #{$investor->getKey()}");

                if (! $investor->user->contract->isActive()) {
                    return;
                }

                $investor->claim_end = $investor->activation_at->addYears(
                    $investor->user->contract->claim_years
                );

                $investor->save();
            });
    }
}
