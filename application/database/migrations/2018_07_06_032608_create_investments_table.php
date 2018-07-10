<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('investor_id');
            $table->unsignedInteger('ext_user_id');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('amount');
            $table->decimal('interest_rate')->default(1);
            $table->unsignedInteger('bonus');
            $table->string('type');
            $table->dateTime('paid_at')->nullable();
            $table->boolean('is_first_investment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
}
