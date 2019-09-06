<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToLinkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_clicks', function (Blueprint $table) {
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
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropIndex('instance_id');
            $table->dropIndex('device');
        });
    }
}
