<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image_url')->nullable();
            $table->string('document_url')->nullable();
            $table->string('image_name')->nullable();
            $table->string('document_name')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('all_users')->default(true);
            $table->boolean('is_blacklist')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
