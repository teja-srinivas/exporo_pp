<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAGBsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_g_bs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('a_g_b_user', function (Blueprint $table) {
            $table->unsignedInteger('a_g_b_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('a_g_b_id')
                ->references('id')
                ->on('a_g_bs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_g_bs');
        Schema::dropIfExists('a_g_b_user');
    }
}
