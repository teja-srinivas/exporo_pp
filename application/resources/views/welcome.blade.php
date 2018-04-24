@extends('layouts.app')

@section('content-class', '')

@section('content')
    <div class="jumbotron text-center welcome-bg-unsharp mb-0">
        <div class="container">
            <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                <h2>Geld verdienen mit Exporo</h2>
                <p class="lead">
                    Auf der innovativen Crowdinvesting-Plattform Exporo können sich Investoren bereits
                    mit Beträgen ab 500€ an interessanten Immobilienprojekten beteilligen und
                    von einer attraktiven Verzinsung mit kurzer Laufzeit profitieren.
                </p>
                <p>
                    <a href="#" class="btn btn-lg btn-primary">Jetzt Partner werden</a>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white has-arrow-down py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                    <h2>Das Exporo Partnerprogramm</h2>
                    <p class="lead">
                        Das Exporo Partnerprogramm bietet Dir die Möglichkeit, mit unseren
                        Immobilienprojekten Geld zu verdienen. Melde Dich einfach kostenlos an.
                    </p>
                </div>
            </div>

            <div class="row mt-4 mb-5">
                <div class="col-md-6">
                    Ob als erfahrener Affiliate, als Privatperson oder Anlageberater
                    – Du kannst mit dem Exporo Partnerprogramm
                    einen finanziellen Nutzen aus Deinen verfügbaren Werbeplätzen
                    (Websites, Blogs, Emails, Content-Portale, etc.)
                </div>
                <div class="col-md-6">
                    ziehen. Gleichzeitig unterstützt Du die Besucher Deiner
                    Website bestmöglich bei der Suche nach attraktiven
                    Anlagemöglichkeiten.
                </div>
            </div>

            <div class="text-center">
                <h4>Das sind Deine Vorteile</h4>

                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase">Einfach</h5>
                        Wir stellen Dir alle Werkzeuge zur Auftragsabwicklung und Rechnungsstellung
                        zur Verfügung. Alles was Du tun mußt, Kunden auf www.exporo.de zu verweisen.
                    </div>
                    <div class="col">
                        <h5 class="text-uppercase">Provision</h5>
                        Für jeden Kunden, der sich über Deinen Link auf www.exporo.de registriert,
                        erhältst Du attraktive Provisionen.
                    </div>
                    <div class="col">
                        <h5 class="text-uppercase">Werbung</h5>
                        Wir stellen Dir konvertierungsstarke und ansprechende Werbemittel zur Verfügung.
                    </div>
                    <div class="col">
                        <h5 class="text-uppercase">Support</h5>
                        Du hast eine Frage? Wir helfen Dir gerne! Du kannst unseren persönlichen
                        und schnellen Support telefonisch, per Chat oder E-Mail nutzen.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="has-arrow-down bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                    <h2>So funktioniert's</h2>
                    <p class="lead">
                        Das Exporo Partnerprogramm bietet Dir die Möglichkeit,
                        mit unseren Immobilienprojekten Geld zu verdienen.
                        Melde Dich einfach kostenlos an.
                    </p>
                </div>
            </div>

            <div class="card-deck my-4">
                <div class="card flex-row">
                    <img src="{{ asset('images/werbemittel_einbinden.png') }}"
                         class="m-2"
                         height="200"
                         aria-hidden>
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">1. Werbemittel einbinden</h5>
                        <hr class="my-2">
                        Platziere unsere Werbemittel auf Deiner Website, in Deinem Blog, poste in
                        den sozialen Medien oder versende Emails– Deiner Kreativität möchten wir keine Grenzen
                        setzen.
                    </div>
                </div>
                <div class="card flex-row">
                    <img src="{{ asset('images/user_engagement.png') }}"
                         class="m-2"
                         height="200"
                         aria-hidden>
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">2. User Engagement</h5>
                        <hr class="my-2">
                        User kommen über ein durch Dich eingesetztes Werbemittel auf eine unserer Seiten.
                    </div>
                </div>
            </div>
            <div class="card-deck">
                <div class="card flex-row">
                    <img src="{{ asset('images/registrierung.png') }}"
                         class="m-2"
                         height="200"
                         aria-hidden>
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">3. Registrierung/Investment</h5>
                        <hr class="my-2">
                        Der Besucher registriert sich auf www.exporo.de und/oder investiert in
                        eines unserer Immobilienprojekte.
                    </div>
                </div>
                <div class="card flex-row">
                    <img src="{{ asset('images/provision.png') }}"
                         class="m-2"
                         height="200"
                         aria-hidden>
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">4. Provision</h5>
                        <hr class="my-2">
                        Du erhälst eine Provision für die Registrierung und/oder das Investment.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="has-arrow-down bg-white py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                    <h2>Zum Exporo Partnerprogramm</h2>
                    <p class="lead">
                        Werde jetzt Partner beim Exporo Partnerprogramm und sichere Dir attraktive Provisionen!
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
