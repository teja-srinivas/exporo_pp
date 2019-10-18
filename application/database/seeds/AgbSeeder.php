<?php

declare(strict_types=1);

use App\Models\Agb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AgbSeeder extends Seeder
{
    /**
     * Creates default AGBs for each AGB type.
     *
     * @return Collection
     */
    public function run(): Collection
    {
        return collect(Agb::TYPES)->map(static function (string $type) {
            return factory(Agb::class)->states('default', $type)->create();
        });
    }
}
