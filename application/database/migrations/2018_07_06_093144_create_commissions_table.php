<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bill_id')->nullable();
            $table->nullableMorphs('model');
            $table->unsignedInteger('user_id')->comment('ID of the internal Partner user');
            $table->decimal('net');
            $table->decimal('gross');
            $table->string('note_private')->nullable()->comment('Internal memo');
            $table->string('note_public')->nullable()->comment('Shown on the Bill to the user');
            $table->dateTime('reviewed_at')->nullable()->comment('indicates when a user has marked this as valid');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->unsignedInteger('rejected_by')->nullable();
            $table->boolean('on_hold')->default(false)->comment('don\'t put this on the bill, ask next time');
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
        Schema::dropIfExists('commissions');
    }
}
