@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        'Mailings',
    ])
@endsection

@section('main-content')
    @card
        @slot('title', 'Kunden per E-Mail Vorlage einladen')

        <p>
            Hier findest du verschiedene E-Mail-Vorlagen, die du verwenden und ergänzen kannst um auf Exporo
            und die aktuellen Immobilienprojekte aufmerksam zu machen. Du kannst den Text einfach kopieren
            und über Dein E-Mail Programm versenden.
        </p>

        @php($user = auth()->user())
        @php($suffix = "?a_aid=" . $user->id)

        <textarea class="form-control" rows="30" readonly>
Lieber Interessent,

eine innovative Anlageform hat sich in den letzten Jahren zu einer aufstrebenden Alternative im Investmentsektor entwickelt. Die Rede ist von Digitalen Immobilieninvestments.

Doch was genau sind Digitale Immobilieninvestments?

Viele Menschen investieren mit relativ kleinen Geldsummen in unterschiedlichste Projekte. Über die Masse kommt dann das Gesamtinvestitionsvolumen zusammen.

Mit der Exporo AG, dem deutschen Marktführer dieser Anlageform, können Sie bereits ab einem Betrag von 500 Euro direkt in ausgewählte Immobilienprojekte investieren. Das Beste daran: Bei einer typischen Laufzeit von 1 - 2 Jahren erhalten Sie eine **Verzinsung von 4 bis 6 % im Jahr**.

Exporo ist für Anleger kostenfrei - durch die schlanken Strukturen der Onlineabwicklung entsteht ein Kostenvorteil, welcher zu dem geringen Mindestinvestment und zu attraktiven Renditen führt.

Hier können Sie sich unverbindlich für weitere Informationen registrieren:
https://p.exporo.de/registrierung/{{ $suffix }}

Bei Fragen zur Plattform oder zu aktuellen Immobilienprojekten ist das Team von Exporo telefonisch unter 040 210 91 73 -00 oder E-Mail über info@exporo.de zu erreichen.

Viele Grüße
{{ $user->first_name }} {{ $user->last_name }}

P.S.: Die Presse hat bereits mehrfach über Exporo berichtet:
https://exporo.de/presse{{ $suffix }}
        </textarea>
    @endcard
@endsection
