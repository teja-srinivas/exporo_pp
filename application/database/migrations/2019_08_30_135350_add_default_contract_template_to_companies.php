<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddDefaultContractTemplateToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', static function (Blueprint $table) {
            $table->unsignedInteger('default_contract_template_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', static function (Blueprint $table) {
            $table->dropColumn('default_contract_template_id');
        });
    }
}
