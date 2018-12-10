@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Meine Subpartner',
        'Werben',
    ])
@endsection

@section('main-content')
    @php($user = auth()->user())
    @php($suffix = '?ref=' . $user->id)

    @foreach([
        'Registrierungs-Landingpage' => 'partnerprogramm.exporo.de/register/',
    ] as $title => $prefix)
        @card
            @slot('title', $title)
            <input type="text" readonly class="form-control form-control-lg" value="{{ $prefix . $suffix }}">
        @endcard
    @endforeach

    @card
    @slot('title', 'Subpartner einladen')

    <p>
        Lade weitere Partner zu unserem Exporo Partnerprogramm ein - auf jeden erfolgreich geworbenen Partner
        erhältst Du Deinen Overhead-Anspruch als Key-Account. Hier findest Du eine E-Mail Vorlage,
        die Du verwenden kannst um auf das Exporo Partnerprogramm aufmerksam zu machen.
        Die Zuordnung des Partners zum Key-Account erfolgt basierend auf der eingegebenen Email
        bzw. ergänzend durch den Partner-Link.
    </p>

    <textarea class="form-control" rows="20" readonly>
Hallo @{{ Name der zu werbenden Person }},

kennst Du schon das Exporo-Partnerprogramm? Nein? Dann wird es höchste Zeit!

Exporo ist eine innovative Crowdinvesting-Plattform – Investoren können sich mit Beträgen ab 500 € an ausgewählten Immobilienprojekten beteiligen und von einer attraktiven Verzinsung mit kurzer Laufzeit profitieren.
Mit dem Exporo-Partnerprogramm kannst auch Du an dem Erfolg  der Immobilienprojekte teilhaben. Das Partnerprogramm ist die Schnittstelle zwischen dem Unternehmen und den Vertriebspartnern: Mit Hilfe von Links, Bannern und E-Mail Vorlagen kannst Du für Exporo werben und erhältst dafür attraktive Provisionen.

Die Anmeldung erfolgt in wenigen Schritten und ist ganz einfach:
https://partnerprogramm.exporo.de/register/{{ $suffix }}

Ich bin bereits Exporo-Partner und kann Dir nur empfehlen, es auch einmal auszuprobieren.

Du hast noch Fragen zum Exporo-Partnerprogramm? Melde Dich gerne bei mir oder wende Dich direkt an Exporo. Das Team ist telefonisch unter 040 210 91 73 -71 oder per E-Mail über partner@exporo.com zu erreichen.

Viele Grüße,
{{ $user->details->display_name }}
        </textarea>
    @endcard
@endsection
