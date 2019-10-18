<?php

declare(strict_types=1);

use App\Models\Schema;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProjectSeeder extends Seeder
{
    /**
     * Creates dummy projects.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var Collection $schemas */
        $schemas = factory(Schema::class, 3)->create();

        $schemas->each(static function (Schema $schema) {
            factory(Project::class, 5)->create([
                'schema_id' => $schema->id,
            ]);

            factory(Project::class, 3)->state('approved')->create([
                'schema_id' => $schema->id,
            ]);
        });
    }
}
