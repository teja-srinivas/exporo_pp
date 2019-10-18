<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddIsOverheadToCommissionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_bonuses', static function (Blueprint $table) {
            $table->boolean('is_overhead')->default(false)->after('is_percentage');
        });
    }
}
