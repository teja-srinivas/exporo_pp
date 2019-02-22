<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsOverheadToCommissionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->boolean('is_overhead')->default(false)->after('is_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->dropColumn('is_overhead');
        });
    }
}
