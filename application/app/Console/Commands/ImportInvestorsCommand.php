<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Investor;

final class ImportInvestorsCommand extends ImportCommand
{
    protected $signature = 'import:investors {updated_at?}';
    protected $description = 'imports investors based on the last updated_at in the user Table';

    protected $model = Investor::class;
    protected $apiUrl = 'exporo.dev/api/partnerprogram/investor';

    protected function importModel(array $investor): void
    {
        Investor::updateOrCreate(
            ['id' => $investor['id']],
            $investor
        );
    }
}
