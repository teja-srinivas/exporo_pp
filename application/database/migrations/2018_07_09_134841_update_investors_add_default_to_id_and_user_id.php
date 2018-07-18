<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvestorsAddDefaultToIdAndUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->unsignedInteger('id')->default()->change();
            $table->unsignedInteger('user_id')->default()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->unsignedInteger('id')->default(null)->change();
            $table->$table->unsignedInteger('user_id')->default(null)->change();
        });
    }
}
