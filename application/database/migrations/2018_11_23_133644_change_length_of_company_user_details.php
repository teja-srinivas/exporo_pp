<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLengthOfBic extends Migration
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
            $table->text('company')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
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
