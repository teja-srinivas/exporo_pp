<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Commission;
use App\Investment;
use App\PartnerPercentages;
use App\Repositories\InvestmentRepository;
use App\Services\CalculateCommissionsService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class CalculateCommissions extends Command
{
    const PER_CHUNK = 480;

    protected $signature = 'calculate:commissions';
    protected $description = 'Calculates commissions from existing investments';

    public function handle(InvestmentRepository $repository, CalculateCommissionsService $commissionsService)
    {
       $repository->queryWithoutCommission()
            ->with('project.schema', 'investor.details')
            ->chunkSimple(self::PER_CHUNK, function (Collection $chunk) use ($commissionsService) {
                $this->line('Calculating ' . $chunk->count() . ' commissions...');
                Commission::query()->insert($this->calculate($commissionsService, $chunk));
            });
    }

    private function calculate(CalculateCommissionsService $commissions, Collection $investments): array
    {
        $now = now();

        return $investments->map(function (Investment $investment) use ($commissions, $now) {
            $sums = $commissions->calculate($investment);

            return $sums + [
                'model_type' => 'investment',
                'model_id' => $investment->id,
                'user_id' => $investment->investor->user_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();
    }
}
