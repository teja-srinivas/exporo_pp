<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddIsProjectTypeToCommissionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_types', static function (Blueprint $table) {
            $table->boolean('is_project_type')->default(false)->after('name');
        });
    }
}
