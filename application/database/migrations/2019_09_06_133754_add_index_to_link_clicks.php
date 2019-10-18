<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddIndexToLinkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_clicks', static function (Blueprint $table) {
            $table->index('instance_id');
            $table->index('device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_clicks', static function (Blueprint $table) {
            $table->dropIndex('instance_id');
            $table->dropIndex('device');
        });
    }
}
