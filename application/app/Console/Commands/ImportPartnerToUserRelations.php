<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Investor;
use App\User;
use App\UserDetails;
use Illuminate\Console\Command;

final class ImportPartnerToUserRelations extends Command
{
    protected $signature = 'import:partnerRelations';
    protected $description = 'imports user/partner relation';

    public function handle()
    {
        if (($handle = fopen(public_path() . '/partnerRelation.csv', 'r')) === false) {
            return;
        }

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            if ($data[1] !== "") {
                Investor::query()->where('id', $data[0])->update(['user_id' => $data[1]]);
            }
        }

        fclose($handle);
    }
}
