<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateMailingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailings', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('text')->nullable();
            $table->timestamps();
        });

        \App\Models\Mailing::query()->create([
            'title' => 'Begrüßung für Interessenten',
            'text' => 'Lieber Interessent,

eine innovative Anlageform hat sich in den letzten Jahren zu einer aufstrebenden Alternative 
im Investmentsektor entwickelt. Die Rede ist von Digitalen Immobilieninvestments.

Doch was genau sind Digitale Immobilieninvestments?

Viele Menschen investieren mit relativ kleinen Geldsummen in unterschiedlichste Projekte. 
Über die Masse kommt dann das Gesamtinvestitionsvolumen zusammen.

Mit der Exporo AG, dem deutschen Marktführer dieser Anlageform, können Sie bereits 
ab einem Betrag von 500 Euro direkt in ausgewählte Immobilienprojekte investieren. 
Das Beste daran: Bei einer typischen Laufzeit von 1 - 2 Jahren erhalten 
Sie eine **Verzinsung von 4 bis 6 % im Jahr**.

Exporo ist für Anleger kostenfrei - durch die schlanken Strukturen der Onlineabwicklung 
entsteht ein Kostenvorteil, welcher zu dem geringen Mindestinvestment und zu attraktiven Renditen führt.

Hier können Sie sich unverbindlich für weitere Informationen registrieren:
https://p.exporo.de/registrierung/#reflink

Bei Fragen zur Plattform oder zu aktuellen Immobilienprojekten ist das Team von Exporo telefonisch 
unter 040 210 91 73 -00 oder E-Mail über info@exporo.de zu erreichen.

Viele Grüße
#partnername

P.S.: Die Presse hat bereits mehrfach über Exporo berichtet:
https://exporo.de/presse/#reflink',
        ]);
    }
}
