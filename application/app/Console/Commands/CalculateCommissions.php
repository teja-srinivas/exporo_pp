<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Commission;
use App\Investment;
use App\Investor;
use App\User;
use App\Repositories\InvestmentRepository;
use App\Services\CalculateCommissionsService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

final class CalculateCommissions extends Command
{
    const PER_CHUNK = 800;

    protected $signature = 'calculate:commissions';
    protected $description = 'Calculates commissions from existing investments';

    public function handle(InvestmentRepository $repository, CalculateCommissionsService $commissionsService)
    {
        $this->calculateInvestments($repository, $commissionsService);
        $this->calculateInvestors($commissionsService);
    }

    private function calculate(string $type, Builder $query, callable $calculate): void
    {
        $query->chunkSimple(self::PER_CHUNK, function (Collection $chunk) use ($type, $calculate) {
            $this->line("Calculating {$chunk->count()} $type commissions...");

            $now = now();
            Commission::query()->insert($chunk->map(function ($entry) use ($now, $type, $calculate) {
                return $calculate($entry) + [
                    'model_type' => $type,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all());
        });
    }

    /**
     * Calculates the commissions for every investor that joined
     * using some sort of affiliate link whose partner now
     * receives a registration bonus because of that.
     *
     * @param CalculateCommissionsService $commissionsService
     */
    private function calculateInvestors(CalculateCommissionsService $commissionsService): void
    {
        // Select essential information of investors where
        // - the partner actually has a registration bonus
        // - the partner has not yet received a bonus
        $query = Investor::query()
            ->select('investors.id', 'investors.user_id')
            ->selectRaw('commission_bonuses.registration')
            ->selectRaw('user_details.vat_included')
            ->join('user_details', 'user_details.id', 'investors.user_id')
            ->leftJoin('commissions', function (JoinClause $join) {
                $join->on('investors.id', 'commissions.model_id');
                $join->where('commissions.model_type', '=', Investor::MORPH_NAME);
            })
            ->leftJoin('commission_bonuses', function (JoinClause $join) {
                $join->on('commission_bonuses.user_id', 'user_details.id');
                $join->where('commission_bonuses.type_id', '=', 3); // FIXME magic number
            })
            ->where('commission_bonuses.registration', '>', 0)
            ->whereNull('commissions.id');

        $callback = function (Investor $investor) use ($commissionsService) {
            $sums = $commissionsService->calculateNetAndGross(
                $investor->vat_included,
                $investor->registration
            );

            return $sums + [
                'model_id' => $investor->id,
                'user_id' => $investor->user_id,
            ];
        };

        $this->calculate(Investor::MORPH_NAME, $query, $callback);
    }

    /**
     * Calculate commissions based on investments for all approved projects.
     *
     * @param InvestmentRepository $repository
     * @param CalculateCommissionsService $commissions
     */
    private function calculateInvestments(
        InvestmentRepository $repository,
        CalculateCommissionsService $commissions
    ): void {
        $query = $repository->queryWithoutCommission()->with([
            'project.schema',
            'investor.details',
            'investor.user',
        ]);

        $callback = function (Investment $investment) use ($commissions) {
            if ($investment->investor->user->parent_id) {
                $this->calculateParentPartner($investment, $investment->investor->user, $commissions);
            }

            return $commissions->calculate($investment) + [
                'model_type' => Investment::MORPH_NAME,
                'model_id' => $investment->id,
                'user_id' => $investment->investor->user_id,
            ];
        };
        $this->calculate(Investment::MORPH_NAME, $query, $callback);
    }


    private function calculateParentPartner(Investment $investment, User $user, CalculateCommissionsService $commission)
    {
        $parent = User::find($user->parent_id);
        if ($parent && $parent->parent_id && $parent->id !== $parent->parent_id) {
        $result = $commission->calculate($investment, $parent, $user);

        Commission::create($result + [
            'model_type' => 'overhead',
            'user_id' => $user->id,
        ]);
            $this->calculateParentPartner($investment, $parent, $commission);
        }
    }
}
