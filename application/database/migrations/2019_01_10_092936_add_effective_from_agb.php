<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddEffectiveFromAgb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agbs', static function (Blueprint $table) {
            $table->date('effective_from')->after('is_default')->nullable();
        });
    }
}
