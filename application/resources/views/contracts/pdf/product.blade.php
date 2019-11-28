@extends('contracts.pdf.layout')
@section('content')
    <h1 style="text-align: center">Anhang "Provisionsvereinbarungen"</h1>
    Folgende Ereignisse führen zu einem Vergütungsanspruch des Partners:
    <h3>1. Ereignisse</h3>
    <p>
        Folgende Ereignisse führen vorbehaltlich der Bestimmungen der
        Vergütungsvereinbarung und dieses Anhangs während der Laufzeit der
        Vergütungsvereinbarung jeweils zu einem Vergütungsanspruch des Partners:
    </p>
    <ol style="list-style-type: lower-roman">
        @if($contract->bonuses->contains('calculation_type', \App\Models\CommissionBonus::TYPE_REGISTRATION))
        <li>
            <strong>Vergütungsanspruch Registrierung </strong><br>
            Ein potentieller Investor registriert sich über von dem Partner zur
            Verfügung gestellten Werbelink erstmals auf der Landingpage <a
                class="external-link"
                href="http://exporo.de/"
                rel="nofollow"
            >https://exporo.de</a>
            bzw. einer anderen von Exporo betriebenen Landingpage (inkl.
            DoubleOpt-in, nachfolgend „<strong>DOI</strong>“) als Nutzer
            (nachfolgend „<strong>Kunden</strong>“).</span>
            <br><br>
        </li>
        @endif
        @if($contract->bonuses->contains('calculation_type', \App\Models\CommissionBonus::TYPE_FIRST_INVESTMENT))
        <li>
            <strong>Vergütungsanspruch Erstinvestment</strong><br>
            Ein vom Partner namhaft gemachter Kunde (z.B. durch Verwendung eines
            Werbelinks) investiert endgültig in ein Finanzprodukt gemäß
            nachstehender Ziffer 2 (Kunden, die auf der Plattform investieren,
            werden nachfolgend als „<strong>Investor</strong>“ bezeichnet).
            Das Investment eines Investors gilt dann als endgültig abgeschlossen, wenn
            Exporo den Investor nicht innerhalb einer Frist von acht Wochen beginnend
            mit der vollständig abgeschlossenen Registrierung nicht abgelehnt hat und
            der vom Partner namhaft gemachte Investor sein Investment über die Plattform
            nicht innerhalb der gesetzlichen Widerrufsfrist von 14-Tagen wirksam
            widerrufen hat.
            <br><br>
        </li>
        @endif
        @if($contract->bonuses->contains('calculation_type', \App\Models\CommissionBonus::TYPE_FURTHER_INVESTMENT))
        <li>
            <strong>Vergütungsanspruch Folgeinvestment</strong><br>
            Der "Vergütungsanspruch Erstinvestment" ist zuvor entstanden und der
            betreffende Investor nimmt nach der Registrierung jeweils eine weitere
            endgültige Investition vor.
            <br><br>
        </li>
        @endif
        <li>
            <strong>Vergütungsanspruch Eigeninvestment</strong><br>
            Der Vergütungsanspruch des Partners gemäß ii. und iii. erstreckt sich auch
            auf Investments, die der Partner selbst im eigenen Namen als sog.
            Eigeninvestments vornimmt.
            <br><br>
        </li>
    </ol>
    <h3><strong>2. Finanzprodukte</strong></h3>
    <div style="margin-left: 30.0px;">
        <p>
            a. Exporo bzw. Unternehmen aus der Exporo Unternehmensgruppe bieten
            verschiedene Finanzprodukte an. Hierbei wird grundsätzlich zwischen zwei
            Produktfamilien unterschieden:
        </p>
        <table class="relative-table wrapped confluenceTable">
                <colgroup>
                    <col style="width: 50%;" />
                    <col style="width: 50%;" />
                </colgroup>
                <tbody>
                <tr>
                    <td style="text-align: left;" class="confluenceTd">
                        <strong>Öffentliche Angebote</strong>
                    </td>
                    <td style="text-align: left;" class="confluenceTd">
                        <strong>Nicht öffentliche Angebote</strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;" class="confluenceTd">
                        Hierzu zählen alle Investmentangebote, die auf
                        <a
                            class="external-link"
                            style="text-decoration: none;"
                            href="http://exporo.de/"
                            rel="nofollow"
                        >https://exporo.de</a>

                        für jeden einsehbar und somit abschließbar sind. Aktuell
                        wird grundsätzlich zwischen zwei unterschiedlichen
                        Produkten unterschieden:

                        <ul>
                            <li>Exporo Finanzierung</li>
                            <li>Exporo Bestand</li>
                        </ul>
                    </td>
                    <td style="text-align: left; vertical-align: top" class="confluenceTd">
                        Hierunter fallen alle Investmentangebote, die nicht über
                        <a
                            class="external-link"
                            style="text-decoration: none;"
                            href="http://exporo.de/"
                            rel="nofollow"
                        >exporo.de</a>

                        öffentlich zugänglich gemacht werden. Eine gesonderte
                        Produktdifferenzierung gibt es nicht.
                    </td>
                </tr>
                </tbody>
            </table>
        <p>
            <strong>b. Produktdefinitionen </strong>
        </p>
        <div style="margin-left: 30.0px;">
            <p>
                i. Exporo Finanzierung: Exporo bietet über die Webseite
                <a
                    class="external-link"
                    style="text-decoration: none;"
                    href="https://exporo.de/"
                    rel="nofollow"
                >https://exporo.de</a>

                den Zugang und das Netzwerk zu professionellen Immobilienprojekten, in
                welche Investoren über die Plattform ab 100€ in eine Vermögensanlage nach §
                1 des Gesetzes über Vermögensanlagen oder in Anleihen investieren können.
                Sowohl bei den Vermögensanlagen sowie bei den Anleihen erwirbt Exporo oder
                ein Unternehmen der Exporo Unternehmensgruppe die Darlehensforderung aus
                einem zweckgebundenen Darlehen eines Kooperationspartners für ein konkretes
                Immobilienprojekt von einem Kreditinstitut. Bei den Vermögensanlagen erwirbt
                der Investor sodann von Exporo oder einem Unternehmen der Exporo
                Unternehmensgruppe eine anteilige Darlehensforderung aus dem zweckgebundenen
                Darlehen. Bei der Anleihe hingegen refinanziert Exporo oder ein Unternehmen
                der Exporo Unternehmesgruppe die Darlehensforderung durch die Emission einer
                Anleihe, die der Investor erwibt. Die Immobilienprojekte sowie der
                Kooperationspartner werden zuvor durch Exporo oder Unternehmen der Exporo
                Unternehmensgruppe geprüft und gelangen erst auf die Plattform, wenn sie dem
                Exporo Maßstab entsprechen. Wie bei einem Bankdarlehen gibt es eine fest
                vereinbarte Laufzeit (zwischen 12 und 36 Monaten) und einen festen Zinssatz
                (üblicherweise zwischen 4-6% p.a.) (nachfolgend "<strong>Exporo Finanzierung</strong>").
            </p>
            <p>
                ii. Exporo Bestand: Exporo bietet über die Webseite
                <a
                    class="external-link"
                    style="text-decoration: none;"
                    href="https://exporo.de/"
                    rel="nofollow"
                >https://exporo.de</a>

                im Rahmen des Produktes "Exporo Bestand" Bestandsobjekte, die in der
                Regel vermietet sind oder vermietet werden sollen. Ein Teil des
                Kaufpreises wird in der Regel über eine Bank finanziert. Über die
                Plattform können sich Investoren in 1.000€-Stücken an diesen Immobilien
                beteiligen. Die Investoren erhalten eine quartalsweise Ausschüttung der
                Mietüberschüsse (i.d.R. 4-5% p.a.). Am Ende der Laufzeit (i.d.R. 10
                Jahre) wird die erzielte Wertsteigerung, sofern vorhanden, beim Verkauf
                der Immobilie zu 80% je nach Anzahl der Anteile an die Investoren
                ausgeschüttet. Exporo übernimmt die gesamte Verwaltung und
                Weiterentwicklung der Immobilie. Zusätzlich können die Investoren die
                Anteile während der gesamten Laufzeit jederzeit über den sog. Exporo
                Handelsplatz wieder liquidieren (nachfolgend "<strong>Exporo Bestand</strong>").
            </p>
        </div>
    </div>
    <p style="margin-left: 30.0px;">
        c. Exporo behält sich das Recht vor, das die bestehenden
        Finanzprodukte jederzeit anzupassen oder durch neue zu erweitern.
        Über Änderungen wird der Partner durch Exporo informiert, indem
        Exporo einen aktualisierten Anhang "Provisionsvereinbarungen" im
        PartnerCockpit hinterlegt. Eine Änderung muss nicht dazu führen, die
        Vergütungsvereinbarung insgesamt neu abgeschlossen werden muss.
    </p>
    <h3><strong>3. Höhe der Vergütung </strong></h3>
    <p>
        a. Auf Basis der unter Ziffer 2 dargestellten Finanzprodukte vereinbart
        Exporo mit dem Partner hiermit die Höhe der Vergütung wie folgt:
    </p>

    @include('components.bundle-editor', [
        'bonuses' => $contract->bonuses,
        'showPublicity' => true,
        'editable' => false,
        'legacy' => true,
    ])

    @if($contract->vat_amount > 0)
    <h3><strong>3. Versteuerung der Provisionsvergütung</strong></h3>
    <p>
        Wenn Bei den Provisionen handelt es sich um sogenannte
        Nettoprovisionen. Sofern der Partner umsatzsteuerpflichtig ist,
        hat die Vergütungsabrechnung die auf die Vergütung entfallende
        Umsatzsteuer (von zur Zeit 19 %) zu enthalten."
    </p>
    @endif

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
