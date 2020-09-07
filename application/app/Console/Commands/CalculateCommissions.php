<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helper\Debug;
use App\Models\User;
use App\Models\Investor;
use App\Builders\Builder;
use App\Models\Commission;
use App\Models\Investment;
use Illuminate\Console\Command;
use App\Models\ProductContract;
use Illuminate\Support\Collection;
use App\Services\CalculateCommissionsService;

final class CalculateCommissions extends Command
{
    // This value has been tuned to a value where it makes the most sense
    // as we eager load data, the more we have in the database the slower
    // our queries become. It is important, that we get just the right
    // amount of data before it becomes too much (e.g. run out of RAM)
    public const PER_CHUNK = 2500;

    protected $signature = 'calculate:commissions';

    protected $description = 'Calculates commissions';

    public function handle(CalculateCommissionsService $commissionsService)
    {
        $this->calculateInvestments($commissionsService);
      //  $this->calculateInvestors($commissionsService);
    }

    private function calculate(Builder $query, callable $calculate, bool $flatten = false): void
    {
        $query->chunk(self::PER_CHUNK, static function (Collection $chunk) use ($calculate, $flatten) {
            $rows = $chunk->map($calculate);

            if ($flatten) {
                $rows = $rows->flatten(1);
            }

            $now = now()->toDateTimeString();
            $instance = new Commission();
            $timestamps = [
                $instance->getCreatedAtColumn() => $now,
                $instance->getUpdatedAtColumn() => $now,
            ];

            Commission::query()->insert($rows->map(static function ($entry) use ($timestamps) {
                return $entry + $timestamps + [
                    'child_user_id' => 0,
                    'note_private' => null,
                    'note_public' => null,
                    'on_hold' => false,
                ];
            })->all());
        });
    }

    private function calculateChunkedByInvestementsId(Builder $query, callable $calculate, bool $flatten = false): void
    {
        //TODO Delete all pending comissions by Observer - this is ugly programming style
        Commission::where('pending', true)->delete();

        // If the number of results is greater than <PER_CHUNK> the native 'chunk' method is not working.
        // Why ever: It´s using always the first <PER_CHUNK> datasets which results in an endless loop.
        // So we have to use 'chunkById' method and everything is fine.
        $query->chunkById(self::PER_CHUNK, static function (Collection $chunk) use ($calculate, $flatten) {
            $rows = $chunk->map($calculate);

            if ($flatten) {
                $rows = $rows->flatten(1);
            }

            $now = now()->toDateTimeString();
            $instance = new Commission();
            $timestamps = [
                $instance->getCreatedAtColumn() => $now,
                $instance->getUpdatedAtColumn() => $now,
            ];

            Commission::query()->insert($rows->map(static function ($entry) use ($timestamps) {
                return $entry + $timestamps + [
                    'child_user_id' => 0,
                    'note_private' => null,
                    'note_public' => null,
                    'on_hold' => false,
                ];
            })->all());
        }, 'investments.id', 'id');
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
        $this->line('Calculating investor commissions...');

        // Select essential information of investors where
        // - the partner actually has a registration bonus
        //   - based on the current contract
        //   - as long as they have a claim on the user
        // - the partner has not yet received a bonus
        $query = Investor::query()
            ->select('investors.id', 'investors.user_id')
            ->addSelect('commission_bonuses.value')
            ->addSelect('contracts.vat_included')
            ->addSelect('contracts.vat_amount')
            ->commissionable();

        $callback = static function (Investor $investor) use ($commissionsService) {
            $sums = $commissionsService->calculateNetAndGross(
                // Temp values that come from the query (not actually from the Investor's table)
                new ProductContract($investor->only('vat_included', 'vat_amount')),
                (float) $investor->value
            );

            return $sums + [
                'model_type' => Investor::MORPH_NAME,
                'model_id' => $investor->id,
                'user_id' => $investor->user_id,
                'bonus' => 0,
            ];
        };

        $this->calculate($query, $callback);
    }

    /**
     * Calculate commissions based on investments for all approved projects.
     *
     * @param CalculateCommissionsService $commissions
     */
    private function calculateInvestments(CalculateCommissionsService $commissions): void
    {
        $this->line('Calculating investment commissions...');

        $query = Investment::query()->withoutCommissions()->with([
            'investor.user.productContract.bonuses',
            'investor.user.details',
            'project.schema',
        ]);

        $userCache = [];

        $callback = static function (Investment $investment) use ($commissions, &$userCache) {
            $entries = [];

            if ($investment->id) {
                $commission = $commissions->calculate($investment);

                if ($commission !== null) {
                    $entries[] = $commission;
                }
            }

            for ($user = $investment->investor->user; $user->parent_id > 0; $user = $parent) {
                $userId = $user->id;
                $parentId = $user->parent_id;

                if ($userId === $parentId) {
                    break;
                }

                /** @var User|null $parent */
                $parent = $userCache[$parentId] ?? null;

                if ($parent === null) {
                    $parent = User::query()->find($parentId, ['id']);
                    $userCache[$parentId] = $parent;
                }

                if (! $parent) {
                    break;
                }

                // We either get an array with the final calculation data, or null
                // in case the user did not have an applicable bonus in which case
                // we will stop traversing the parent chain
                $sums = $commissions->calculate($investment, $parent, $user);

                if ($sums === null) {
                    break;
                }

                $entries[] = $sums + ['child_user_id' => $userId];
            }

            return $entries;
        };

        print $query->getRawSqlWithBindings();

        $this->calculateChunkedByInvestementsId($query, $callback, true);
    }
}
