<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Project;

final class ImportProjectsCommand extends ImportCommand
{
    protected $signature = 'import:projects {updated_at?}';
    protected $description = 'imports projects based on the last updated_at in the user Table';

    protected $model = Project::class;
    protected $apiUrl = 'exporo.dev/api/partnerprogram/project';

    protected function importModel(array $project): void
    {
        Project::updateOrCreate(
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
