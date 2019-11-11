<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdfGeneratedAtToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', static function (Blueprint $table) {
            $table->string('signature')->nullable()->after('allow_overhead');
            $table->dateTime('pdf_generated_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', static function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->dropColumn('pdf_generated_at');
        });
    }
}
