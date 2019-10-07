<?php

use App\Models\BannerSet;

class ConvertBannerSetUrlsToBannerLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BannerSet::query()->each(function (BannerSet $set) {
            foreach ($set->urls as $url) {
                $set->links()->create([
                    'title' => $url['key'],
                    'url' => str_replace('#reflink', '${reflink}', $url['value']),
                ]);
            }
        });
    }
}
