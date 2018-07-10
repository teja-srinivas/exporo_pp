<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->unsignedInteger('id')->comment('External Project ID');
            $table->unsignedInteger('client_id')->comment('Internal Mandant ID');
            $table->string('name');
            $table->decimal('interest_rate');
            $table->string('term');
            $table->decimal('margin');
            $table->string('type');
            $table->string('image');
            $table->text('description');
            $table->unsignedInteger('scheme_id');
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
        Schema::dropIfExists('projects');
    }
}
