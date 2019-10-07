<?php

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
        Schema::table('agbs', function (Blueprint $table) {
            $table->date('effective_from')->after('is_default')->nullable();
        });
    }
}
