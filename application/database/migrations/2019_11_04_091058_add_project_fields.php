<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', static function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('rating')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->decimal('coupon_rate')->nullable();
            $table->decimal('funding_target')->nullable();
            $table->decimal('funding_current_sum_invested')->nullable();
            $table->string('intermediator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', static function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('rating');
            $table->dropColumn('type');
            $table->dropColumn('status');
            $table->dropColumn('coupon_rate');
            $table->dropColumn('funding_target');
            $table->dropColumn('funding_current_sum_invested');
            $table->dropColumn('intermediator');
        });
    }
}
