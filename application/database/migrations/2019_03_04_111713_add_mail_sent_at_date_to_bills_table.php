<?php

use App\Models\Bill;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailSentAtDateToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->timestamp('pdf_created_at')->nullable()->after('released_at');
        });

        Bill::query()->each(function (Bill $bill) {
            if (! $bill->pdf_created) {
                return;
            }

            $bill->pdf_created_at = $bill->updated_at;
            $bill->save();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('pdf_created');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->timestamp('mail_send_at')->nullable()->after('pdf_created_at');
        });
    }
}
