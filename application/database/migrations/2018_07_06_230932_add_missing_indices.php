<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->index(['user_id']);
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->index(['investor_id']);
            $table->index(['project_id']);
        });

        Schema::table('investors', function (Blueprint $table) {
            $table->index(['user_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index(['schema_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->dropIndex(['investor_id']);
            $table->dropIndex(['project_id']);
        });

        Schema::table('investors', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['schema_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });
    }
}
