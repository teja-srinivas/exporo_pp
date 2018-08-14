<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Commission;
use App\Investment;
use App\Investor;
use App\UserDetails;
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
        $this->createRegistrationCommission($commissionsService);
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

    private function createRegistrationCommission(CalculateCommissionsService $commissionsService)
    {
        $users = $this->getUsersWithRegistrationProvision();

        foreach ($users as $user)
        {
            $provision = $user->registration_bonus;
            $provision = $commissionsService->calculateRegistration($user, $provision);
            $investors = $this->getInvestorRegistrationProvision($user->id);


            foreach ($investors as $investor){
                Commission::create([
                    'model_type' => 'investor',
                    'model_id' => $investor->id,
                    'user_id' => $user->id,
                    'net' => $provision['net'],
                    'gross' => $provision['gross']
                ]);

            }
        }
    }

    private function getInvestorRegistrationProvision(int $userId): Collection
    {
        return Investor::query()
            ->doesntHave('commissions')
            ->whereIn('user_id', [$userId])
            ->get();
    }

    private function getUsersWithRegistrationProvision(): Collection
    {
      return UserDetails::where('registration_bonus', '!=', 'NULL')
            ->where('registration_bonus', '!=', 0.00)
            ->get();
    }
}
