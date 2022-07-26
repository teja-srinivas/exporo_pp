<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('url')->nullable();
            $table->timestamps();
        });

        \App\Models\Link::query()->create([
            'title' => 'Startseite',
            'url' => 'https://exporo.de/#reflink',
        ]);

        \App\Models\Link::query()->create([
            'title' => 'Registrierungs-Landingpage',
            'url' => 'https://p.exporo.de/registrierung/#reflink',
        ]);
    }
}
