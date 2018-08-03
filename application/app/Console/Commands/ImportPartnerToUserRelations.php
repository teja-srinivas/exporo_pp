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
        if (($handle = fopen ( public_path () . '/partnerRelation.csv', 'r' )) !== FALSE) {
            while ( ($data = fgetcsv ( $handle, 0, ';' )) !== FALSE ) {
                if($data[1] !== ""){
                    Investor::where('id', $data[0])
                    ->update(['user_id' => $data[1]]);
                }
            }
            fclose ( $handle );
        }
    }
}
