<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateProvisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provisions', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('user_id');
            $table->decimal('first_investment')->nullable();
            $table->decimal('further_investment')->nullable();
            $table->decimal('registration')->nullable();
            $table->timestamps();
        });
    }
}
