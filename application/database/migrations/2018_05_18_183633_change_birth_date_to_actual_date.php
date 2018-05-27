<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBirthDateToActualDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->fixEnumSupport();

        Schema::table('user_details', function (Blueprint $table) {
            $table->date('birth_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->fixEnumSupport();

        Schema::table('user_details', function (Blueprint $table) {
            $table->string('birth_date')->change();
        });
    }

    protected function fixEnumSupport(): void
    {
        // Fix for renaming/updating a column inside a table that has enums
        // See https://github.com/laravel/framework/issues/1186 for more information
        $doctrine = DB::connection($this->getConnection())->getDoctrineConnection();
        $dbPlatform = $doctrine->getSchemaManager()->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping('enum', 'string');
    }
}