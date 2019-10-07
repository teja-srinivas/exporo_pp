<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RemoveUserIdFromCommissionBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
