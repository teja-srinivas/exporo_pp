<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvestmentsAddDefaultToId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->unsignedInteger('id')->default()->change();
            $table->unsignedInteger('investor_id')->default()->change();
            $table->unsignedInteger('project_id')->default()->change();
            $table->unsignedInteger('amount')->default(0)->change();
            $table->unsignedInteger('bonus')->default(0)->change();
            $table->boolean('is_first_investment')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->unsignedInteger('id')->default(null)->change();
            $table->unsignedInteger('investor_id')->default(null)->change();
            $table->unsignedInteger('project_id')->default(null)->change();
            $table->unsignedInteger('amount')->default(null)->change();
        });
    }
}
