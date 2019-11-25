@extends('contracts.pdf.layout')

@section('content')
<p style="text-align: center;"><strong>Tippgebervereinarung</strong></p>
<p style="text-align: center;">zwischen</p>
<p>der Firma Exporo Investment GmbH,<br>vertr. durch die Geschäftsleitung, Am Sandtorkai 70, 20457 Hamburg</p>
<p style="text-align: right;">- im Folgenden „Exporo“ genannt -</p>
<p style="text-align: center;">und</p>
<p>
    @unless(empty($contract->user->details->company))
        der Firma {{ $contract->user->details->company }}<br>
        vertr. durch
    @endunless
    {{ $contract->user->getDisplayName() }}
    {{ $contract->user->details->address_street }}
    {{ $contract->user->details->address_number }},
    {{ $contract->user->details->address_zipcode }}
    {{ $contract->user->details->address_city }}
</p>
<p style="text-align: right;">- im Folgenden „Partner“ genannt –</p>
<p><strong>Präambel</strong></p>
<p>
    Exporo betreibt unter anderem die Webseite
    <a href="https://exporo.de">https://exporo.de</a>, über die Anleger in
    Immobilien- und Immobilienprojekte investieren sowie Unternehmen
    (nachfolgend auch "Kooperationspartner") Kapital für Immobilien und
    Immobilienprojekte einwerben können (nachfolgend auch "Plattform").
</p>
<p>
    <br />Exporo hat darüber hinaus eine Webseite unter
    <a href="https://partnerprogramm.exporo.de">https://partnerprogramm.exporo.de</a>
    eingerichtet, über die sich Dritte als Tippgeber von Exporo registrieren und
    mit den von Exporo auf der Webseite für die Tippgeber zur Verfügung
    gestellten Werbematerialien potentielle Investoren auf die Plattform oder
    Exporo hinweisen können. Für den Fall, dass der Tippgeber Investoren
    vermittelt, die auf der Plattform von Exporo in Produkte investieren,
    beabsichtigt Exporo dem Tippgeber unter bestimmten Voraussetzungen eine
    Provision zu zahlen. Der Partner ist daran interessiert, als Tippgeber tätig
    zu werden. Dies vorausgeschickt vereinbaren Exporo und der Partner folgende
    Vereinbarung:
</p>
<h3><strong>1. Vertragsgegenstand und Vertragsgrundlage</strong></h3>
<p style="margin-left: 2rem;">
    Der Partner ist für die Laufzeit dieses Vertrages hiermit berechtigt, aber
    nicht verpflichtet, im Rahmen des Partnerprogramms als Tippgeber tätig zu
    werden.
</p>
<p style="margin-left: 2rem;">
    Die Tätigkeit des Tippgebers erstreckt sich auf die gelegentliche
    Namhaftmachung der Plattform oder von Exporo bzw. Gesellschaften der Exporo
    Gruppe gegenüber potentiellen Investoren. Die Namhaftmachung durch den
    Tippgeber erfolgt in der Regel durch die Verwendung von den von Exporo im
    Partnercockpit gemäß § 2 der "Allgemeinen Geschäftsbedingungen für die
    Nutzung des Exporo Partnerprogramms" (nachfolgend auch "AGB") hinterlegten
    Werbematerialien wie z.B. Banner, Produktdaten,Text-Links, E-Mails, Videos
    etc. mit integrierten Hoplinks, indem der Partner diese auf einer von ihm
    betriebenen Homepage oder Webseite oder einen eigenen Blog veröffentlicht
    oder einer E-Mail an potentielle Investoren versendet
    ("<strong>Namhaftmachung</strong>").
</p>
<p style="margin-left: 2rem;">
    Wenn und soweit der Partner auf der Plattform gelistete Finanzprodukte im
    Rahmen seiner Tätigkeit als Tippgeber vergleicht, darf sich dieser Vergleich
    nur auf wenige Merkmale der Finanzprodukte (z.B. Zins und Laufzeit)
    erstrecken. Die vollständige Produktvorstellung ist nur über die Plattform
    zulässig. Daher ist die Nennung von Exporo als Plattformbetreiber erwünscht,
    nicht aber die Nennung des Emittenten. Es muss zudem klar erkennbar sein,
    dass es sich hierbei nicht um ein Angebot handelt, welches der Partner
    vermittelt. Es muss aus der Darstellung hervorgehen, dass die Produkte über
    die Plattform angeboten werden. Dem Partner ist es untersagt, bewusst und
    final auf einen potentiellen Investor einzuwirken, damit dieser ein Geschäft
    über die Anschaffung oder über die Veräußerung von Finanzinstrumenten
    abschließt.
</p>
<p style="margin-left: 2rem;">
    Der Partner verpflichtet sich hiermit ausdrücklich, als Tippgeber keine
    eigene Beratung von potentiellen Investoren in Bezug auf die Plattform und
    die mit ihr verbundenen Investmentmöglichkeiten vorzunehmen und gegenüber
    den potentiellen Investoren nicht auf einen Vertragsschluss
    hinzuwirken.Konkrete Finanzprodukte von Exporo werden durch den Tippgeber
    gerade nicht unmittelbar angeboten oder vermittelt.
</p>
<p style="margin-left: 2rem;">
    Für den Partner ist die Teilnahme als Tippgeber am Partnerprogramm gegenüber
    Exporo frei von Kosten.
</p>
<p style="margin-left: 2rem;">
    Vertragsgrundlage für die Teilnahme als Tippgeber am Partnerprogramm sind
    über die vorstehenden Bestimmungen hinaus sämtliche Bestimmungen dieser
    Vergütungsvereinbarung, der AGB in der jeweils aktuellen Fassung sowie
    ergänzend die Bestimmungen gemäß Anhang "Provisionsvereinbarungen". Bei
    etwaigen Widersprüchen zwischen dieser Vergütungsvereinbarung und den AGB
    gehen die Bestimmungen dieser Vergütungsvereinbarung den AGB vor.
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;">
    Ergänzend ist es dem Partner gestattet, gegenüber Exporo seinerseits
    potentielle Tippgeber namhaft zu machen und diese Exporo als neue Partner
    zuzuführen (nachfolgend „<strong>Sub-Partner</strong>“). Für die Zuführung
    von Sub-Partnern erhält der Partner eine gesonderte Vergütung gemäß<span
        >&nbsp;</span
    ><span
        >Ziffer 2. des Anhangs "Provisionsvereinbarungen"</span
    >. Exporo ist berechtigt, die Aufnahme der namhaft gemachten Sub-Partner
    ohne Angabe von Gründen abzulehnen. Exporo weist den Partner vorsorglich
    darauf hin, dass es etwaigen Sub-Partnern nicht gestattet ist, ebenfalls
    neue Partner als Sub-Sub-Partner namhaft zu machen. Vielmehr beschränkt sich
    die Tätigkeit der Sub-Partner auf die Namhaftmachung neuer potentieller
    Investoren.
</p>
@endif

@if($contract->is_exclusive)
<p style="margin-left: 2rem;">
    Der Partner ist während der Laufzeit dieses Vertrages dergestalt exklusiv
    für Exporo tätig, dass er etwaige Maßnahmen zur Kundengewinnung für Digitale
    Immobilieninvestments ausschließlich über Exporo abwickelt bzw. potentielle
    Investoren ausschließlich Exporo namhaft macht.
</p>
@endif

<p style="margin-left: 2rem;">
    Diese Vereinbarung&nbsp;ersetzt&nbsp;alle etwaigen
    früheren&nbsp;Vereinbarungen&nbsp;zwischen den Parteien.
</p>
<h3><strong>2. Vergütungsanspruch des Partners</strong></h3>
<p style="margin-left: 2rem;">
    <strong
        ><span
            >a. Entstehen des Vergütungsanspruchs</span
        ></strong
    >
</p>
<p style="margin-left: 2rem;">
    <span
        >Der Partner erhält von Exporo eine erfolgsabhängige Vergütung
        (nachfolgend auch "<strong>Provision</strong>" oder
        "<strong>Vergütung</strong>" genannt) für die in dem Anhang
        "Provisionsvereinbarungen" definierten Ereignisse.&nbsp;</span
    >
</p>
<p style="margin-left: 2rem;">
    <strong>b. Entfallen des Vergütungsanspruchs</strong>
</p>
<p style="margin-left: 2rem;">
    Dem Partner ist bekannt, dass Exporo die Registrierung eines Nutzers bzw.
    die Vermittlung eines Investments mit dem Kunden über die Plattform ablehnen
    kann. Der Partner hat insoweit keinen Anspruch auf die Registrierung oder
    die Durchführung des Investments sowie deren Provision. Eine Ablehnung kommt
    namentlich insbesondere in folgenden Konstellationen in Betracht, ohne dass
    die Aufzählung abschließend wäre:
</p>
<p style="margin-left: 4rem;">
    1.&nbsp;der Kunde ist nicht volljährig oder sonst nicht geschäftsfähig;
</p>
<p style="margin-left: 4rem;">
    2. der Kunde ist keine natürliche Person (zum Beispiel bei Registrierungen
    über automatische Scripts/Bots oder ähnlich);
</p>
<p style="margin-left: 4rem;">
    3. der Kunde ist bereits registrierter Nutzer bzw. bestehender Kunde von
    Exporo oder Unternehmen der Exporo Gruppe;
</p>
<p style="margin-left: 4rem;">
    4. es bestehen Anhaltspunkte dafür, dass der Kunde tatsächlich nicht
    existiert oder tatsächlich kein Aufbau einer Geschäftsbeziehung beabsichtigt
    ist (z.B. bei der Verwendung sog. Fake-Accounts, der Verwendung sog.
    „Wegwerf-Emailadressen“ oder der Eingabe nicht sinnvoller Daten in das
    Anmeldeformular).
</p>
<p style="margin-left: 4rem;">
    5. Exporo weist den Partner darauf hin, dass es
    den<span>&nbsp;</span>Kooperationspartnern<strong>&nbsp;</strong>ebenfalls
    freisteht, das Investment eines Kunden abzulehnen.
</p>
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>c. Berechnung und Höhe der Vergütung&nbsp;</strong>
</p>
<p style="margin-left: 4rem;">
    <span
        >i. Die Höhe der Vergütung, die von der Höhe des Investments des
        Investors auf der Plattform abhängig ist, berechnet sich auf eine
        Laufzeit des Finanzproduktes von 24 Monaten. Sollten Finanzprodukte mit
        Laufzeiten von weniger als 24 Monaten vom Investor gezeichnet werden, so
        berechnet sich die Vergütung anteilig auf die kürzere Laufzeit, da
        Exporo von den Kooperationspartnern grundsätzlich eine deutlich
        geringere Vergütung bei kurzen Laufzeiten der Finanzprodukte erhält. Bei
        einer Laufzeit von 18 Monaten zum Beispiel berechnet sich die Vergütung
        wie folgt: 18 dividiert durch 24 und multipliziert mit der Vergütung
        laut des gewählten Faktors&nbsp;von 1,5 % bzw. 2,5 % gleich 1,125 % bzw.
        1,875. Liegt die Laufzeit des Finanzprodukts über einer Laufzeit von 24
        Monaten, erhöht sich die Vergütung des Partners nicht.</span
    >
</p>
<p style="margin-left: 4rem;">
    <span
        ><span
            >ii. Sofern einem durch den Partner namhaft gemachtem Kunden
            Vorzugskonditionen für den Abschluss eines Investments angeboten
            werden, unabhängig davon von welcher Partei die Vorzugskonditionen
            angeboten werden, ist Exporo dazu berechtigt die Vorzugskonditionen
            anteilig oder in voller Höhe von der Vergütung des Partners
            abzuziehen.&nbsp;</span
        ><br
    /></span>
</p>
<p style="margin-left: 4rem;">
    <span
        >iii. Sieht sich Exporo aus Markt- oder Wettbewerbsgründen veranlasst,
        in Einzelfällen eine ungewöhnlich niedrige eigene Vergütung bei einem
        Geschäft von seinem Kooperationspartner zu erhalten, so kann die dem
        Partner für diese Geschäfte zustehende Vergütung angemessen gekürzt
        werden. Namentlich soll der Gesamtbetrag aus der Vergütung für die
        Namhaftmachung von potentiellen Investoren gemäß<span>&nbsp;</span
        ><span
            >Ziffer 2. des Anhangs "Provisionsvereinbarungen"</span
        >&nbsp;beschränkt werden auf einen Betrag in Höhe von maximal 50%
        derjenigen Vergütung, die Exporo für das jeweilige Geschäft von seinem
        Kooperationspartner zusteht.&nbsp;</span
    >
</p>
<p style="margin-left: 4rem;">
    <span
        ><span
            >iv. Sollte ein Kunde bei der Registrierung keinem Tippgeber
            zugeordnet sein, besteht bei nachträglicher Zuordnung des Kunden zu
            dem Partner seitens des Partners kein Anspruch auf eine Vergütung
            von Ansprüchen, die in der Vergangenheit liegen</span
        >.</span
    >
</p>
<p style="margin-left: 4rem;">
    v. Ein Vergütungsanspruch für Investments eines Kunden kann bestehen,
    sofern ein Investment binnen eines Zeitraumes von {{ $contract->claim_years }}
    Jahren nach der Registrierung des Kunden erfolgt
    ist.
</p>
<p style="margin-left: 4rem;">
    vi. Mit der Vergütung gemäß des Anhangs&nbsp;<span
        >„</span
    >Provisionsvereinbarungen" zu dieser Vergütungsvereinbarung ist die
    Tätigkeit des Partners im Rahmen des Partnerprogramms vollumfänglich
    abgegolten; weitergehende Vergütungs- oder Aufwendungsersatzansprüche
    bestehen nicht.
</p>
<p style="margin-left: 4rem;">
    vii.&nbsp;Der Vergütungsanspruch des Partners gemäß vorstehendem lit. a.
    entfällt, wenn und soweit die Ausführung und Abwicklung des aufgrund seines
    Zutuns abgeschlossenen Investments aus welchen Gründen auch immer
    rückabgewickelt wird oder von Anfang unwirksam ist. Bereits bezahlte
    Vergütungen sind in diesen Fällen zurückzuzahlen.
</p>
<p style="margin-left: 4rem;">
    <span
        >viii. Die Aufrechnung mit gegenüber Exporo bestehenden Forderungen oder
        die Zurückbehaltung von Zahlungen wegen solcher Ansprüche ist nur
        zulässig, soweit die Gegenansprüche unbestritten, entscheidungsreif oder
        rechtskräftig festgestellt sind.</span
    >
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;">
    <strong>d. Namhaftmachung von potentiellen Sub-Partner</strong>
</p>
<p style="margin-left: 2rem;">
    <span
        >Macht der Partner gegenüber Exporo einen Sub-Partner namhaft und nimmt
        der Sub-Partner als selbst als Tippgeber an dem Partnerprogramm teil, so
        erhält der Partner eine Vergütung<span>&nbsp;</span>gemäß Ziffer 3. des
        Anhangs "Provisionsvereinbarungen".</span
    >
</p>
<p style="margin-left: 4rem;">
    <span
        >1.&nbsp;Der Anspruch des Partners auf Vergütung besteht nur dann, wenn
        der Sub-Partner selber einen Anspruch auf Vergütung hat. Sollte der
        namhaftgemachte Sub-Partner die Zusammenarbeit beenden oder aus
        sonstigen Gründen keine Provision erhalten, erhält auch der Partner
        keine Vergütung<span>&nbsp;</span>nach dieser
        Vergütungsvereinbarung.</span
    >
</p>
<p style="margin-left: 4rem;">
    <span
        >2. Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen
        eine ungewöhnlich niedrige eigene Vergütung aus einem Projekt mit seinen
        Kooperationspartnern zu akzeptieren, so kann die dem Partner für diese
        Geschäfte zustehende Vergütung angemessen gekürzt werden. Namentlich
        soll der Gesamtbetrag aus der Vergütung für die Namhaftmachung von
        potentiellen Investoren gemäß&nbsp;<span
            >Ziffer 2. des Anhangs "Provisionsvereinbarungen"&nbsp;</span
        >und der Vergütung für die Namhaftmachung von potenziellen Sub-Partnern
        gemäß&nbsp;<span
            >Ziffer 3. des Anhangs "Provisionsvereinbarungen".</span
        ><span>&nbsp;</span>beschränkt werden auf einen Betrag in Höhe von
        maximal 50% derjenigen Vergütung, die Exporo für das jeweilige Geschäft
        von seinem Kooperationspartner zusteht</span
    >
</p>
<p style="margin-left: 4rem;">
    <span
        >3. Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen
        mit einem Sub-Partner eine zusätzliche Vergütung zu vereinbaren, so kann
        die dem Partner für diese Geschäfte zustehende Vergütung ebenfalls
        angemessen gekürzt werden. Die Kürzung erfolgt über eine (ggf.
        anteilige) Reduzierung der Höhe der Vergütung gemäß&nbsp;<span
            >Ziffer 2. des Anhangs "Provisionsvereinbarungen".</span
        ></span
    >
</p>
@endif

<p style="margin-left: 4rem;"><br /></p>
<div class="table-wrap">
    <p class="auto-cursor-target">
        <strong style="font-size: 1.142em;"
            >3. Fälligkeit / Abrechnung der Vergütung</strong
        >
    </p>
</div>
<p style="margin-left: 2rem;">
    a. Exporo erteilt dem Partner monatlich zum 20. eines Monats eine Abrechnung
    über die im jeweiligen Vormonat entstandenen Vergütungen. Sollte der 20. auf
    einen Sonn- oder Feiertag fallen, so verschiebt sich die Abrechnung auf den
    folgenden Werktag. Die Abrechnung wird im Partner Cockpit hinterlegt. Die
    Vergütung wird jeweils einem Vergütungskonto gutgeschrieben. Das Guthaben
    des Vergütungskontos wird jeweils bis zum Ende des Kalendermonats per
    Banküberweisung ausgezahlt.
</p>
<p style="margin-left: 2rem;">
    b. Widerspricht der Partner einer Abrechnung nicht binnen einer Frist von 4
    Wochen nach Erhalt der jeweiligen Abrechnung, so gilt diese Abrechnung als
    ausdrücklich anerkannt.<span>&nbsp;</span>Exporo ist darüber hinaus
    berechtigt, den Partner in regelmäßigen<span>&nbsp;</span>Abständen zur
    Anerkenntnis der erteilten Abrechnungen über die Vergütung aufzufordern.
</p>
<h3>
    <span
        ><strong>4. Versteuerung der Einkünfte&nbsp;</strong></span
    >
</h3>
<p style="margin-left: 2rem;">
    <span
        >a. Für sämtliche gewerbliche oder behördliche Erlaubnispflichten bzw.
        Zulassungen, die sich aus der Tätigkeit des Partners ergeben, ist der
        Partner selbst zuständig. Der Partner erklärt, dass er seinen
        Verpflichtungen insoweit nachkommt und gegenüber Exporo hierüber auf
        Anforderung einen entsprechenden Nachweis erbringen kann&nbsp;(z.B.
        Gewerbeanmeldung, Ust.-IdNr.),</span
    >
</p>
<p style="margin-left: 2rem;">
    <span
        >b. Für den Fall, dass die seitens Exporo gezahlten Vergütungen entgegen
        der §§ 4 Nr. 8 lit. F und 11 UstG (1999) von der Finanzverwaltung als
        umsatzsteuerpflichtig bewertet werden sollten, vereinbaren die
        Vertragsparteien ausdrücklich, dass diese Umsatzsteuer bereits in der
        Vergütung gemäß&nbsp;<span
            >des Anhangs "Provisionsvereinbarungen"&nbsp;</span
        >enthalten ist. Der Ausweis&nbsp;der gesetzlichen Ust. erfolgt erst ab
        dem Monat, in dem die Mitteilung darüber eingegangen ist. Eine wegen
        verspäteter Mitteilung rückwirkende Korrektur von bereits ohne Ust.
        ausgestellter Gutschriften erfolgt nicht.</span
    >
</p>
<p style="margin-left: 2rem;">
    <span
        >c.&nbsp;Als selbstständiger Tippgeber ist der Partner für die
        Einhaltung der rechtmäßigen Steuerzahlungen zu der Vergütung gemäß<span
            >&nbsp;</span
        ><span
            >des Anhangs "Provisionsvereinbarungen"&nbsp;</span
        >und der Meldung an sein zuständiges&nbsp;Finanzamt&nbsp;als auch für
        die sozialversicherungsrechtlichen Bestimmungen selbst
        verantwortlich.</span
    >
</p>
<p style="margin-left: 2rem;">
    <span
        >d.&nbsp;<span
            >Sofern der Ausweis der Ust. beantragt wird, erfolgt die Erhebung
            grundsätzlich für jede Einzelbuchung. Die Darstellung erfolgt
            aufsummiert.</span
        ></span
    >
</p>
<h3>
    <strong>5. Laufzeit und Beendigung, Vertragsänderungen</strong>
</h3>
<p>
    a. Diese Vereinbarung tritt mit Unterzeichnung, frühestens jedoch zum
    01.01.2020, in Kraft und wird auf unbestimmte Zeit geschlossen.
</p>
<p>
    b. Beide Parteien sind berechtigt, diese Vereinbarung mit einer Frist
    von {{ $contract->cancellation_days }} Tag(en) zu kündigen. Das Recht zur außerordentlichen Kündigung
    aus wichtigem Grund bleibt hiervon unberührt.
</p>
<p>
    <span
        ><span
            >c. Der Partner ist berechtigt, einen Antrag auf Änderung seines
            Provisionsschemas gemäß Ziffer 2 Anhang "Provisionsvereinbarungen"
            zu stellen. Der Antrag kann entweder über seinen direkten
            Ansprechpartner erfolgen oder über&nbsp;<a
                class="external-link"
                style="text-decoration: none;"
                href="mailto:abrechnung@exporo.com."
                rel="nofollow"
                >abrechnung@exporo.com.</a
            >&nbsp;Sofern der Änderung zugestimmt wird, wird dem Partner in
            seinem Cockpit der neue Anhang zur Verfügung gestellt.&nbsp;</span
        ></span
    >
</p>
<h3><strong>6. Sondervereinbarung / Nebenabreden</strong></h3>
<p>
    @empty($contract->special_agreement)
        Nebenabreden zu diesem Vertrag sind nicht getroffen.
    @else
        {{ $$contract->special_agreement }}
    @endempty
</p>
<p><br /></p>
<p>
    <strong style="font-size: 1.142em;">7. Schlussbestimmungen</strong>
</p>
<p>
    a. Änderungen oder Ergänzungen dieses Vertrages, einschließlich dieser
    Schriftformklausel, bedürfen zu ihrer Wirksamkeit der Schriftform. Gleiches
    gilt für Neben- und Zusatzabreden.
</p>
<p>
    b. Diese Vereinbarung einschließlich ihrer Anlagen und das Rechtsverhältnis
    zwischen Exporo und dem Partner unterliegen dem Recht der Bundesrepublik
    Deutschland.
</p>
<p>
    c. Wenn der Partner Unternehmer ist, sind Erfüllungsort für alle Leistungen
    und Gerichtsstand für sämtliche Auseinandersetzungen aus und im Zusammenhang
    mit diesem Vertrag, soweit dies gesetzlich zulässig ist, Hamburg. Dies gilt
    auch dann, wenn der Partner keinen allgemeinen Gerichtsstand im Inland hat.
    Exporo behält sich das Recht vor, den Partner an seinem allgemeinen
    Gerichtsstand zu verklagen. Gesetzliche Regelungen über ausschließliche
    Zuständigkeiten bleiben unberührt.
</p>
<p>
    d. Sollten sich einzelne Bestimmungen dieser Vereinbarung als ungültig oder
    undurchführbar erweisen, so wird dadurch die Gültigkeit der Vereinbarung im
    Übrigen nicht berührt. In einem solchen Fall sind die Parteien verpflichtet,
    die ungültige oder undurchführbare Bestimmung durch diejenige gesetzlich
    zulässige Bestimmung zu ersetzen, die den Zweck der ungültigen oder
    undurchführbaren Bestimmung, insbesondere das, was die Parteien gewollt
    haben, mit der weitestgehend möglichen Annäherung erreicht. Entsprechendes
    gilt, wenn sich bei Durchführung der Vereinbarung eine ergänzungsbedürftige
    Lücke ergeben sollte.
</p>

<table style="min-width: 50vw; margin-top: 3rem">
    <tr>
        <td>
            <div style="border-bottom: 1px solid black">
                @if($contract->accepted_at !== null)
                {{ $contract->user->company->city }},
                {{ optional($contract->accepted_at)->format('d.m.Y') }}
                @endif
            </div>
            <p style="margin-top: 0.25rem">Ort, Datum</p>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding-top: 3rem"></td>
    </tr>
    <tr>
        <td>
            <div style="border-bottom: 1px solid black">
                [bild von unterschrift]
            </div>
            <p style="margin-top: 0.25rem">Exporo Investment GmbH</p>
        </td>
        <td style="width: 3rem"></td>
        <td style="width: 50%">
            <div style="border-bottom: 1px solid black">
                @if($contract->signature !== '')
                {{ $contract->signature ?? '' }}
                @endif
            </div>
            <p style="margin-top: 0.25rem">Partner</p>
        </td>
    </tr>
</table>
@endsection
