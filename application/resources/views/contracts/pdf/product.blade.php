@extends('contracts.pdf.layout')
@section('content')
    <h1 style="text-align: center">Anhang "Provisionsvereinbarungen"-1</h1>
    <h3><strong>1. Finanzprodukte</strong></h3>
    <div style="margin-left: 2rem;">
        <p>
            a. Exporo bzw. Unternehmen aus der Exporo Unternehmensgruppe bieten 
            verschiedene Finanzprodukte an. Hierbei wird grundsätzlich zwischen
            zwei Produktfamilien unterschieden:
        </p>
        <table class="relative-table wrapped confluenceTable">
                <colgroup>
                    <col style="width: 50%;" />
                    <col style="width: 50%;" />
                </colgroup>
                <tbody>
                <tr>
                    <td style="text-align: left;">
                        <strong>Öffentliche Angebote*</strong>
                    </td>
                    <td style="text-align: left;">
                        <strong>Nicht öffentliche Angebote*</strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        Hierzu zählen alle Investmentangebote, die auf
                        <a href="http://exporo.de/">https://exporo.de</a>
                        für jeden einsehbar und somit abschließbar sind. Aktuell
                        wird grundsätzlich zwischen zwei unterschiedlichen
                        Produkten unterschieden:

                        <ul>
                            <li>Exporo Finanzierung</li>
                            <li>Exporo Bestand</li>
                        </ul>
                    </td>
                    <td style="text-align: left;">
                        Hierunter fallen alle Investmentangebote,
                        die von Exporo als sog. Private
                        Placements oder PP bezeichnet werden.
                        Die Einordnung erfolgt im Ermessen von
                        Exporo.
                    </td>
                </tr>
                </tbody>
            </table>
        <p>
            * Die Einordnung der Finanzprodukte in öffentliche und nicht
            öffentliche Angebote wird von Exporo vorgenommen und entspricht
            ausdrücklich nicht den Definitionen des Art. 2 lit. d EU-Prospekt-VO.
        </p>

        <p>
            <strong>b. Produktdefinitionen </strong>
        </p>
        <div style="margin-left: 2rem;">
            <p>
                i.  Exporo Finanzierung: Exporo bietet über die Webseite 
                <a href="http://exporo.de/">https://exporo.de</a> den Zugang und
                das Netzwerk zu professionellen Immobilienprojekten, in welche
                Investoren über die Plattform ab 100€ in eine Vermögensanlage
                nach § 1 des Gesetzes über Vermögensanlagen oder in Anleihen 
                investieren können. Sowohl bei den Vermögensanlagen sowie bei
                den Anleihen erwirbt Exporo oder ein Unternehmen der Exporo 
                Unternehmensgruppe die Darlehensforderung aus einem zweckgebundenen
                Darlehen eines Kooperationspartners für ein konkretes 
                Immobilienprojekt von einem Kreditinstitut. Bei den Vermögensanlagen
                erwirbt der Investor sodann von Exporo oder einem Unternehmen
                der Exporo Unternehmensgruppe eine anteilige Darlehensforderung
                aus dem zweckgebundenen Darlehen. Bei der Anleihe hingegen 
                refinanziert Exporo oder ein Unternehmen
                der Exporo Unternehmesgruppe die Darlehensforderung durch die
                Emission einer Anleihe, die der Investor erwibt. Die
                Immobilienprojekte sowie der Kooperationspartner werden zuvor 
                durch Exporo oder Unternehmen der Exporo Unternehmensgruppe 
                geprüft und gelangen erst auf die Plattform, wenn sie dem 
                Exporo Maßstab entsprechen. Wie bei einem Bankdarlehen gibt es 
                eine fest vereinbarte Laufzeit (zwischen 12 und 36 Monaten) und 
                einen festen Zinssatz (üblicherweise zwischen 4-6% p.a.) 
                (nachfolgend "<strong>Exporo Finanzierung</strong>").
            </p>
            <p>
                ii. Exporo Bestand: Exporo bietet über die Webseite
                <a href="http://exporo.de/">https://exporo.de</a> im Rahmen des
                Produktes "Exporo Bestand" Bestandsobjekte, die in der Regel
                vermietet sind oder vermietet werden sollen. Ein Teil des
                Kaufpreises wird in der Regel über eine Bank finanziert. Über
                die Plattform können sich Investoren in 1.000€-Stücken an diesen
                Immobilien beteiligen. Die Investoren erhalten eine quartalsweise
                Ausschüttung der Mietüberschüsse (i.d.R. 4-5% p.a.). Am Ende der
                Laufzeit (i.d.R. 10 Jahre) wird die erzielte Wertsteigerung,
                sofern vorhanden, beim Verkauf der Immobilie zu 80% je nach
                Anzahl der Anteile an die Investoren ausgeschüttet. Exporo
                übernimmt die gesamte Verwaltung und Weiterentwicklung der
                Immobilie (nachfolgend "<strong>Exporo Bestand</strong>").
            </p>
            <p>
                iii. Exporo behält sich das Recht vor, die bestehenden
                Finanzprodukte jederzeit anzupassen oder durch neue zu
                erweitern. Über Änderungen wird der Partner durch Exporo
                informiert, indem Exporo einen aktualisierten Anhang 
                "Provisionsvereinbarungen" im Partner Cockpit hinterlegt. 
                Eine Änderung muss nicht dazu führen, dass die 
                Vergütungsvereinbarung insgesamt neu abgeschlossen werden muss.
            </p>
        </div>
    </div>
    
    <h3><strong>3. Höhe der Vergütung </strong></h3>
    <p style="margin-left: 2rem;">
        a. Ist ein Vergütungsanspruch entstanden, vereinbart Exporo mit dem 
        Partner hiermit für die Berechnung der Vergütung folgende Beträge:
    </p>

    @include('components.bundle-editor', [
        'bonuses' => $contract->bonuses,
        'showPublicity' => true,
        'editable' => false,
        'legacy' => true,
    ])

    <p>
        Der Partner, dessen Sub-Partner ein Endgültiges Investment vermittelt 
        hat, erhält eine Overhead-Partner Provision. Bei der Berechnung der
        Overhead-Partner Provision ist der für den Overhead-Partner für das 
        jeweilige Finanzprodukt vereinbarte Prozentsatz heranzuziehen und
        von diesem Prozentsatz ist die Provision des Sub-Partners zu 
        subtrahieren. Beispiel: Wurde mit dem Overhead-Partner eine Höhe 
        von 2,50 % vereinbart und erhält der Sub-Partner für das Investment 
        3,00 %, so ergibt sich für den Overhead-Partner einen für die Berechnung 
        der Vergütung heranzuziehenden Prozentsatz von 0,50 %.
    </p>

    <h3><strong>3. Umsatzsteuer</strong></h3>
    <p>
        Die Provisionen werden von Exporo umsatzsteuerfrei in Rechnung gestellt.
        // Sollte der Partner gegenüber Exporo anzeigen, dass seine
        Tätigkeit der Umsatzsteuerpflicht unterliegt, wird Exporo diese in der 
        Abrechnung gesondert ausweisen/ wird die Provision inklusive der
        Umsatzsteuer ausgewiesen.
    </p>

    <!-- Scripts -->
    <script src="{{ url(mix('js/manifest.js')) }}"></script>
    <script src="{{ url(mix('js/vendor.js')) }}"></script>
    <script src="{{ url(mix('js/app.js')) }}"></script>

    <style>
        .table {
            border-collapse: collapse;
            margin: 15px auto 50px;
        }

        .table th {
            text-align: center;
        }

        .table td {
            border: 1px solid #555;
            padding: 2px 10px;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline;
            border: 1px solid #888;
            border-radius: 0.25rem;
            padding: 0 3px;
            font-size: 0.75rem;
        }
    </style>
@endsection
