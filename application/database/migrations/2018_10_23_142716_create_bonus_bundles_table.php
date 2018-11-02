<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('selectable');
            $table->string('name')->comment('Internal label for archiving purposes');
            $table->timestamps();

            $table->index('selectable');
        });

        Schema::create('bonus_bundle', function (Blueprint $table) {
            $table->unsignedInteger('bonus_id');
            $table->unsignedInteger('bundle_id');

            $table->unique(['bonus_id', 'bundle_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('bonus_bundle');
    }
}
