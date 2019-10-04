<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeLinkInstancesMorphable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_instances', function (Blueprint $table) {
            $table->string('link_type')->after('id');

            $table->index(['link_type', 'link_id']);
        });

        // Set default value
        DB::table('link_instances')->update(['link_type' => 'link']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_instanced', function (Blueprint $table) {
            $table->dropColumn(['link_type', 'link_id']);
            $table->dropColumn('link_type');
        });
    }
}
