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
        Schema::create('agbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('agb_user', function (Blueprint $table) {
            $table->unsignedInteger('agb_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('agb_id')
                ->references('id')
                ->on('agbs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['agb_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agbs');
        Schema::dropIfExists('agb_user');
    }
}
