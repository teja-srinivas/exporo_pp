<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateBonusBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', static function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('selectable');
            $table->string('name')->comment('Internal label for archiving purposes');
            $table->timestamps();

            $table->index('selectable');
        });

        Schema::create('bonus_bundle', static function (Blueprint $table) {
            $table->unsignedInteger('bonus_id');
            $table->unsignedInteger('bundle_id');

            $table->unique(['bonus_id', 'bundle_id']);
        });
    }
}
