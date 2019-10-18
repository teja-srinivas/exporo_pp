<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FixMailSentAtColumnInBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', static function (Blueprint $table) {
            $table->renameColumn('mail_send_at', 'mail_sent_at');
        });
    }
}
