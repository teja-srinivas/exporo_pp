<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailingsVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailings', static function (Blueprint $table) {
            $table->json('variables')->after('html')->nullable();
        });
    }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
    public function down()
    {
        Schema::table('mailings', static function (Blueprint $table) {
            $table->dropColumn(['variables']);
        });
    }
}
