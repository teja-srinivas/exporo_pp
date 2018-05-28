<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedInteger('last_user_id');
            $table->timestamps();

            $table->primary('id');
        });

        Schema::create('investor_user', function (Blueprint $table) {
            $table->unsignedInteger('investor_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('editor_id')->comment('External user ID');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investors');
        Schema::dropIfExists('investor_user');
    }
}
