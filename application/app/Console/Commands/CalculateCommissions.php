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
    // This value has been tuned to a value where it makes the most sense
    // as we eager load data, the more we have in the database the slower
    // our queries become. It is important, that we get just the right
    // amount of data before it becomes too much (e.g. run out of RAM)
    const PER_CHUNK = 2500;

    protected $signature = 'calculate:commissions';
    protected $description = 'Calculates commissions from existing investments';

    public function handle(InvestmentRepository $repository, CalculateCommissionsService $commissionsService)
    {
        $this->calculateInvestments($repository, $commissionsService);
        $this->calculateInvestors($commissionsService);
    }

    private function calculate(string $type, Builder $query, callable $calculate, bool $flatten = false): void
    {
        $query->chunkSimple(self::PER_CHUNK, function (Collection $chunk) use ($type, $calculate, $flatten) {
            $this->line("Calculating {$chunk->count()} $type commissions...");

            $rows = $chunk->map($calculate);

            if ($flatten) {
                $rows = $rows->flatten(1);
            }

            $now = now()->toDateTimeString();

            Commission::query()->insert($rows->map(function ($entry) use ($now, $type) {
                return $entry + [
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
            // Temp values that come from the query (not actually from the Investor's table)
                (bool)$investor->vat_included,
                (float)$investor->registration
            );

            return $sums + [
                    'model_id' => $investor->id,
                    'user_id' => $investor->user_id,
                    'bonus' => 0,
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
    ): void
    {
        $query = $repository->queryWithoutCommission()->with([
            'project.schema',
            'investor.details',
            'investor.user.bonuses',
        ]);

        $callback = function (Investment $investment) use ($commissions) {
            $entries = [];
            $entries[] = $commissions->calculate($investment) + [
                    'model_id' => $investment->id,
                    'user_id' => $investment->investor->user_id,
                ];

            for ($user = $investment->investor->user; $user->parent_id > 0; $user = $parent) {
                if ($user->id === $user->parent_id) {
                    break;
                }

                $parent = User::query()->find($user->parent_id, ['id']);

                if (!$parent) {
                    break;
                }

                $entries[] = $commissions->calculate($investment, $parent, $user) + [
                        'model_type' => Investment::OVERHEAD_MORPH_NAME,
                        'model_id' => $investment->id,
                        'user_id' => $user->id,
                    ];
            }

            return $entries;
        };

        $this->calculate(Investment::MORPH_NAME, $query, $callback, true);
    }
}
