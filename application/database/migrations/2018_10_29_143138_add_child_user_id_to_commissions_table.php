<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddChildUserIdToCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions', static function (Blueprint $table) {
            $table->unsignedInteger('child_user_id')
                ->default(0)
                ->after('user_id')
                ->index();
        });
    }
}
