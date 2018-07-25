<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Investment;

final class ImportInvestmentsCommand extends ImportCommand
{
    protected $signature = 'import:investments {updated_at?}';
    protected $description = 'imports investments based on the last updated_at in the user table';

    protected $model = Investment::class;
    protected $apiUrl = 'exporo.dev/api/partnerprogram/investment';

    protected function importModel(array $investment): void
    {
        Investment::query()->updateOrCreate(
            ['id' => $investment['id']],
            [
                'id' => $investment['id'],
                'investor_id' => $investment['user_id'],
                'amount' => $investment['amount'],
                'acknowledged_at' => $investment['acknowledged_at'],
                'created_at' => $investment['created_at'],
                'updated_at' => $investment['updated_at'],
                'project_id' => $investment['node_id'],
                'interest_rate' => $investment['rate'],
                'is_first_investment' => $investment['is_first_investment'],
                'paid_at' => $investment['paid_at']
            ]
        );
    }
}
