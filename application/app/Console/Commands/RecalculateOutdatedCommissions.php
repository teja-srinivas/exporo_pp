<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Investment;
use App\Repositories\InvestmentRepository;
use App\Services\CalculateCommissionsService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class RecalculateOutdatedCommissions extends Command
{
    const PER_CHUNK = 480;

    protected $signature = 'calculate:outdated-commissions';
    protected $description = 'Recalculates commissions if any related data has been updated in the meantime';


    public function handle(InvestmentRepository $investments, CalculateCommissionsService $service)
    {
        $investments->whereCalculationChangedQuery()
            ->with('project.schema', 'investor.details')
            ->chunkSimple(self::PER_CHUNK, function (Collection $chunk) use ($service) {
                $this->line('Calculating ' . $chunk->count() . ' commissions...');

                $chunk->each(function (Investment $investment) use ($service) {
                    $investment->commissions()->update($service->calculate($investment));
                    $this->touchModels($investment);
                });
            });
    }

    private function touchModels(Investment $investment): void
    {
        $investment->investor->touch();
        $investment->project->touch();
        $investment->project->schema->touch();
        $investment->touch();
    }
}
