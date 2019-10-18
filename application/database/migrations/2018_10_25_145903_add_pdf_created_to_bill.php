<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddPdfCreatedToBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', static function (Blueprint $table) {
            $table->boolean('pdf_created')->after('released_at')->default(0);
        });
    }
}
