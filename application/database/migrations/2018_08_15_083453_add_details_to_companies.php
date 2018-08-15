<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('street')->nullable();
            $table->string('street_no')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('street_no');
            $table->dropColumn('postal_code');
            $table->dropColumn('city');
            $table->dropColumn('phone_number');
            $table->dropColumn('fax_number');
            $table->dropColumn('email');
            $table->dropColumn('website');
        });
    }
}
