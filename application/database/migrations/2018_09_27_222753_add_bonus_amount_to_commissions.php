<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddBonusAmountToCommissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions', static function (Blueprint $table) {
            $table->decimal('bonus', 10, 4)->after('gross')->default(0);
        });
    }
}
