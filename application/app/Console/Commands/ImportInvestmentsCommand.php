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
                'amount' => $investment['investsum'],
                'created_at' => $investment['created_at'],
                'updated_at' => $investment['updated_at'],
                'project_id' => $investment['project_nid'],
                'interest_rate' => $investment['rate'],
            ]
        );
    }
}
