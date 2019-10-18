<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DropBonusFieldsFromUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', static function (Blueprint $table) {
            $table->dropColumn([
                'registration_bonus',
                'first_investment_bonus',
                'further_investment_bonus',
            ]);
        });
    }
}
