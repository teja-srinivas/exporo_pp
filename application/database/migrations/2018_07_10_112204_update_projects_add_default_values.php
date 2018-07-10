<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsAddDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('interest_rate')->default(0)->change();
            $table->unsignedInteger('margin')->default(0)->change();
            $table->string('image')->default(0)->change();
            $table->text('description')->nullable()->change();
            $table->unsignedInteger('schema_id')->default(0)->change();
            $table->decimal('capital_cost')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('interest_rate')->default(NULL)->change();
            $table->unsignedInteger('margin')->default(NULL)->change();
            $table->string('image')->default(NULL)->change();
            $table->text('description')->nullable()->change();
            $table->unsignedInteger('schema_id')->default(NULL)->change();
            $table->decimal('capital_cost')->default(NULL)->change();
        });
    }
}
