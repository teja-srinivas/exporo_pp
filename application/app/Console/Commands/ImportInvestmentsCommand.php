<?php
declare(strict_types=1);


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Investment;
use GuzzleHttp;

final class ImportInvestmentsCommand extends Command
{

    protected $signature = 'importInvestments {updated_at?}';
    protected $description = 'imports investments based on the last updated_at in the user table';

    public function handle()
    {
        $client = new GuzzleHttp\Client();
        $updated_at = $this->argument('updated_at', 0);
        if (!$updated_at) {
            $updated_at = Investment::getNewestUpdatedAtDate();
        }

        $nextLink = 'exporo.dev/api/partnerprogram/investment?api-token=12341&updated_at=' . $updated_at;
        while ($nextLink) {
            $response = GuzzleHttp\json_decode($client->get($nextLink)->getBody()->getContents(), true);
            $nextLink = $response['next'];
            $this->writeInvestmentsToDatabase($response);
        }
    }

    private function writeInvestmentsToDatabase($investments)
    {
        foreach ($investments['items'] as $investment) {
            if ($investment['created_at'] === '0000-00-00 00:00:00') {
                continue;
            }
            Investment::updateOrCreate(
                ['id' => $investment['id']],
                [
                    'id' => $investment['id'],
                    'investor_id' => $investment['user_id'],
                    'investsum' => $investment['investsum'],
                    'created_at' => $investment['created_at'],
                    'updated_at' => $investment['updated_at'],
                    'project_id' => $investment['project_nid'],
                ]
            );
        }
    }
}
