<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDatabaseSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->decimal('vat_amount')->default(19);
            $table->boolean('vat_included')->default(true);
            $table->unsignedInteger('parent_id')->default(0);
            $table->decimal('registration_bonus')->default(0);
            $table->decimal('first_investment_bonus')->default(0);
            $table->decimal('further_investment_bonus')->default(0);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('client_id', 'term');
            $table->renameColumn('scheme_id', 'schema_id');

            $table->decimal('capital_cost');
            $table->date('launched_at')->nullable();
            $table->date('payback_min_at')->nullable();
            $table->date('payback_max_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
        });

        Schema::dropIfExists('investor_user');

        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn('ext_user_id', 'last_user_id', 'project_id', 'partner_id');
            $table->renameColumn('client_id', 'user_id');
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn('ext_user_id', 'type');

            $table->dateTime('acknowledged_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cannot replicate as before the migration
    }
}
