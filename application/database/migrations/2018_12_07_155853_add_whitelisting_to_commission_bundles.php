<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddWhitelistingToCommissionBundles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundles', static function (Blueprint $table) {
            $table->boolean('child_user_selectable')->default(true)->after('selectable');
        });
    }
}
