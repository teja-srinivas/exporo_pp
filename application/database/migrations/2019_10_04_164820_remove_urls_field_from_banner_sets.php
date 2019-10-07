<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RemoveUrlsFieldFromBannerSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_sets', function (Blueprint $table) {
            $table->dropColumn('urls');
        });
    }
}
