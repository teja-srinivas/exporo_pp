<?php

use App\Models\UserDetails;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertVatIncludedToVatStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserDetails::query()
            ->whereNull('vat_included')
            ->orWhere('vat_included', '')
            ->update([
                'vat_included' => false,
                'vat_amount' => 0,
            ]);

        // Fix for renaming/updating a column inside a table that has enums
        // See https://github.com/laravel/framework/issues/1186 for more information
        $doctrine = DB::connection($this->getConnection())->getDoctrineConnection();
        $dbPlatform = $doctrine->getSchemaManager()->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('user_details', function (Blueprint $table) {
            $table->decimal('vat_amount', 8, 2)->default(0)->change();
            $table->boolean('vat_included')->nullable(false)->default(false)->change();
        });
    }
}
