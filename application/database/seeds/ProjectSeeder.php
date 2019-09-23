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
     * @return Collection
     */
    public function run(): Collection
    {
        // Create 3 schemas for which we have 10 projects each
        return factory(Schema::class, 3)->create()->flatMap(function (Schema $schema) {
            return factory(Project::class, 10)->create([
                'schema_id' => $schema->id,
            ]);
        });
    }
}
