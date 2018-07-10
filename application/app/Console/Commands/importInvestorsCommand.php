<?php
declare(strict_types=1);


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;
use App\Investor;
use GuzzleHttp;

final class importInvestorsCommand extends Command
{
    protected $signature = 'importInvestors {updated_at?}';
    protected $description = 'imports investors based on the last updated_at in the user Table';

    public function handle()
    {
        $client = new GuzzleHttp\Client();
        $updated_at = $this->argument('updated_at', 0);
        if (!$updated_at) {
            $updated_at = Investor::getNewestUpdatedAtDate();
        }

        $nextLink = 'exporo.dev/api/partnerprogram/user?api-token=12341&updated_at=' . $updated_at;
        while ($nextLink) {
            $response = GuzzleHttp\json_decode($client->get($nextLink)->getBody()->getContents(), true);
            $nextLink = $response['next'];
            $this->writeInvestorToDatabase($response);
        }
    }

    private function writeInvestorToDatabase($investors)
    {
        foreach ($investors['items'] as $investor) {
            if($investor['created_at'] === '0000-00-00 00:00:00'){
                continue;
            }
            App\Investor::updateOrCreate(
                ['id' => $investor['id']],
                $investor
            );
        }
    }
}
