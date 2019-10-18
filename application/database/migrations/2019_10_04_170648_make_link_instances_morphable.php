<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MakeLinkInstancesMorphable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_instances', static function (Blueprint $table) {
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
        Schema::table('link_instanced', static function (Blueprint $table) {
            $table->dropColumn(['link_type', 'link_id']);
            $table->dropColumn('link_type');
        });
    }
}
