<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ChangeLengthOfCompanyUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->fixEnumSupport();

        Schema::table('user_details', static function (Blueprint $table) {
            $table->text('company')->change();
        });
    }
}
