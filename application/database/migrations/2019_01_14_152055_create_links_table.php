<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('url')->nullable();
            $table->timestamps();
        });

        \App\Models\Link::query()->create([
            'title' => 'Startseite',
            'url' => 'https://exporo.de/#reflink'
        ]);

        \App\Models\Link::query()->create([
            'title' => 'Registrierungs-Landingpage',
            'url' => 'https://p.exporo.de/registrierung/#reflink'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
