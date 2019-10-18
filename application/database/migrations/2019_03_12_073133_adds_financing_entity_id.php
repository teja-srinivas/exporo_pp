<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddsFinancingEntityId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', static function (Blueprint $table) {
            $table->string('financing_entity_id')->after('id')->nullable();
        });
    }
}
