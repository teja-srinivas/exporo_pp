<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('set_id')->index();
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->string('filename');
            $table->timestamps();
        });
    }
}
