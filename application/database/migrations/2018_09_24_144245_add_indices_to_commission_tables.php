<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddIndicesToCommissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->index('type_id');
            $table->index('user_id');
        });
    }
}
