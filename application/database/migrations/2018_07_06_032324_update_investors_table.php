<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('partner_id');
            $table->unsignedInteger('ext_user_id');
            $table->date('claim_end')->nullable();
            $table->date('activated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn([
                'project_id',
                'client_id',
                'partner_id',
                'ext_user_id',
                'claim_end',
                'activated_at',
            ]);
        });
    }
}
