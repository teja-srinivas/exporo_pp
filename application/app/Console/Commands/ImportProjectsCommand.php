<?php
declare(strict_types=1);


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;
use App\Project;
use GuzzleHttp;

final class ImportProjectsCommand extends Command
{
    protected $signature = 'importProjects {updated_at?}';
    protected $description = 'imports projects based on the last updated_at in the user Table';

    public function handle()
    {
        $client = new GuzzleHttp\Client();
        $updated_at = $this->argument('updated_at', 0);
        if (!$updated_at) {
            $updated_at = Project::getNewestUpdatedAtDate();
        }

        $nextLink = 'exporo.dev/api/partnerprogram/project?api-token=12341&updated_at=' . $updated_at;
        while ($nextLink) {
            $response = GuzzleHttp\json_decode($client->get($nextLink)->getBody()->getContents(), true);
            $nextLink = $response['next'];
            $this->writeProjectsToDatabase($response);
        }
    }

    private function writeProjectsToDatabase($projects)
    {
        foreach ($projects['items'] as $project) {
            if ($project['created_at'] === '0000-00-00 00:00:00') {
                continue;
            }
            App\Project::updateOrCreate(
                ['id' => $project['nid']],
                [
                    'id' => $project['nid'],
                    'created_at' => $project['created_at'],
                    'updated_at' => $project['updated_at'],
                    'name' => $project['slug'],
                    'type' => $project['type'],
                ]
            );
        }
    }
}
