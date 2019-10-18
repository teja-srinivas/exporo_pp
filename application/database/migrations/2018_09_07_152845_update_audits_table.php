<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', static function (Blueprint $table) {
            $table->string('user_type')->after('user_id')->nullable();
            $table->index(['user_id', 'user_type']);
        });
    }
}
