<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddBonusIdToInvestments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investments', static function (Blueprint $table) {
            $table->string('bonus_id')->after('bonus')->nullable();
        });
    }
}
