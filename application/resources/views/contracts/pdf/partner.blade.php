@extends('contracts.pdf.layout')

@section('content')
<h1>Tippgebervereinbarung</h1>
<p style="text-align: center;"><strong>Tippgebervereinbarung</strong></p>
<p style="text-align: center;">zwischen</p>
<p>
  der Exporo Investment GmbH,<br>Am Sandtorkai 70, 20457 Hamburg,
  eingetragen im Handelsregister des Amtsgericht Hamburg unter HRB
  146341, vertreten durch ihren Prokuristen Patrick Hartmann,
</p>
<p style="text-align: right;">- im Folgenden „Exporo“ genannt -</p>
<p style="text-align: center;">und</p>
<p>
    @unless(empty($contract->user->details->company))
        der Firma {{ $contract->user->details->company }}<br>
        vertr. durch
    @endunless
    {{ $contract->user->first_name }}
    {{ $contract->user->last_name }},<br>
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
    Immobilien und Immobilienprojekte investieren sowie Unternehmen
    (nachfolgend auch "<strong>Kooperationspartner</strong>") Kapital für Immobilien und
    Immobilienprojekte einwerben können (nachfolgend auch "<strong>Plattform</strong>").
</p>
<p>
    <br />Exporo hat darüber hinaus eine Webseite unter
    <a href="https://partnerprogramm.exporo.de">https://partnerprogramm.exporo.de</a>
     eingerichtet, über die sich Dritte als Tippgeber von
    Exporo registrieren und u.a. mit den von Exporo auf der Webseite für die
    Tippgeber zur Verfügung gestellten Werbematerialien potentielle
    Investoren auf die Plattform oder Exporo hinweisen können. Für
    den Fall, dass der Tippgeber erfolgreich Investoren vermittelt, die in
    Produkte von Exporo investieren, beabsichtigt Exporo dem Tippgeber
    unter bestimmten Voraussetzungen eine Provision zu zahlen
    ("Partnerprogramm"). Der Partner ist daran interessiert, als
    Tippgeber im Sinne dieser Vereinbarung tätig zu werden. Dies vorausgeschickt
    vereinbaren Exporo und der Partner folgende Vereinbarung:
</p>
<h3><strong>1. Vertragsgegenstand</strong></h3>
<p style="margin-left: 2rem;">
    Der Partner ist für die Laufzeit dieses Vertrages hiermit berechtigt, aber
    nicht verpflichtet, im Rahmen des Partnerprogramms als Tippgeber tätig zu
    werden.
</p>
<p style="margin-left: 2rem;">
    Die Tätigkeit des Tippgebers erstreckt sich auf die gelegentliche
    Namhaftmachung der Plattform oder von Exporo bzw.
    Gesellschaften der Exporo Gruppe gegenüber potentiellen Investoren.
    Die Namhaftmachung durch den Tippgeber erfolgt in der Regel
    durch die Verwendung von den von Exporo im Partnercockpit gemäß § 2
    der "Allgemeinen Geschäftsbedingungen für die Nutzung
    des Exporo Partnerprogramms" (nachfolgend auch "<strong>AGB</strong>") hinterlegten
    Werbematerialien wie z.B. Banner, Produktdaten, Text-Links,
    E-Mails, Videos etc. mit integrierten Hoplinks, indem der Partner
    diese auf einer von ihm betriebenen Homepage oder Webseite oder
    einen eigenen Blog veröffentlicht oder einer E-Mail an potentielle
    Investoren versendet ("<strong>Namhaftmachung</strong>").
</p>
<p style="margin-left: 2rem;">
    Wenn und soweit der Partner auf der Plattform gelistete Finanzprodukte im
    Rahmen seiner Tätigkeit als Tippgeber vergleicht, darf
    sich dieser Vergleich nur auf wenige Merkmale der Finanzprodukte (z.B. Zins
    und Produkt Typ) erstrecken. Die vollständige
    Produktvorstellung ist nur über die Plattform zulässig. Daher ist die Nennung
    von Exporo als Betreiber der Plattform erwünscht, nicht
    aber die Nennung des Emittenten. Es muss zudem klar erkennbar sein, dass es
    sich hierbei nicht um ein Angebot handelt, welches der
    Partner vermittelt. Es muss aus der Darstellung hervorgehen, dass die Produkte
    über die Plattform angeboten werden. Dem Partner
    ist es untersagt, bewusst und final auf einen potentiellen Investor einzuwirken,
    damit dieser ein Geschäft über die Anschaffung oder
    über die Veräußerung von Finanzinstrumenten abschließt. 
</p>
<p style="margin-left: 2rem;">
    Der Partner verpflichtet sich hiermit ausdrücklich, als Tippgeber keine
    eigene Beratung von potentiellen Investoren in Bezug auf
    Investmentmöglichkeiten vorzunehmen und gegenüber den potentiellen Investoren
    nicht auf einen Vertragsschluss
    hinzuwirken. Konkrete Finanzprodukte von Exporo werden durch den Tippgeber
    gerade nicht unmittelbar angeboten oder vermittelt.
</p>
<p style="margin-left: 2rem;">
    Für den Partner ist die Teilnahme als Tippgeber am Partnerprogramm gegenüber
    Exporo frei von Kosten. 
</p>
<p style="margin-left: 2rem;">
    Vertragsgrundlage für die Teilnahme als Tippgeber am Partnerprogramm sind
    über die vorstehenden Bestimmungen hinaus
    sämtliche Bestimmungen dieser Vergütungsvereinbarung, der AGB in der jeweils
    aktuellen Fassung sowie ergänzend die
    Bestimmungen gemäß Anhang "Provisionsvereinbarungen". Bei etwaigen 
    Widersprüchen zwischen dieser Vergütungsvereinbarung
    und den AGB gehen die Bestimmungen dieser Vergütungsvereinbarung den AGB vor.
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;">
    Ergänzend ist es dem Partner gestattet, gegenüber Exporo seinerseits
    potentielle Tippgeber namhaft zu machen und diese Exporo als neue Partner
    zuzuführen (nachfolgend „<strong>Sub-Partner</strong>“). Für die
    Zuführung von Sub-Partnern erhält der Partner eine gesonderte Vergütung
    gemäß Ziffer 2. des Anhangs "Provisionsvereinbarungen".
    Exporo ist berechtigt, die Aufnahme der namhaft gemachten Sub-Partner ohne
    Angabe von Gründen abzulehnen. Exporo weist den
    Partner vorsorglich darauf hin, dass es etwaigen Sub-Partnern nicht
    gestattet ist, ebenfalls neue Partner als Sub-Sub-Partner
    namhaft zu machen. Vielmehr beschränkt sich die Tätigkeit der Sub-Partner
    auf die Namhaftmachung neuer potentieller Investoren.
</p>
@endif

@if($contract->is_exclusive)
<p style="margin-left: 2rem;">
    Der Partner ist während der Laufzeit dieses Vertrages dergestalt
    exklusiv für Exporo tätig, dass er in Bezug auf digitale
    Immobilieninvestments ausschließlich die Plattform bzw. Exporo gegenüber
    potentiellen Investoren namhaft macht.
</p>
@endif

<p style="margin-left: 2rem;">
    Diese Vereinbarung ersetzt hiermit ab dem Wirksamwerden dieser
    Vereinbarung alle etwaigen früheren Vereinbarungen über die
    Teilnahme am Partnerprogramm zwischen den Parteien.
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
    <span>
        Der Partner erhält, vorbehaltlich der Bestimmungen dieser
        Vergütungsvereinbarung, dem Anhang "Provisionsvereinbarungen" und
        der AGB, von Exporo eine erfolgsabhängige Vergütung (nachfolgend
        auch "<strong>Provision</strong>" oder "<strong>Vergütung</strong>"
        genannt) für die folgende Ereignisse. 
    </span>
</p>
<p style="margin-left: 4rem;">
    <strong>i. Vergütungsanspruch Registrierung</strong>
</p>
<p style="margin-left: 4rem;">
    Ein potentieller Investor registriert sich über einen von dem Partner zur
    Verfügung gestellten Werbelink oder Hoplink erstmals
    auf der Seite <a href="https://exporo.de">https://exporo.de</a> bzw.
    einer anderen von Exporo betriebenen Landingpage (inkl. DoubleOpt-in,
    nachfolgend „<strong>DOI</strong>“) als Nutzer (nachfolgend
    „<strong>Kunde</strong>“) ("<strong>Registrierung</strong>").
</p>
<p style="margin-left: 4rem;">
    <strong>ii. Vergütungsanspruch Erstinvestment</strong>
</p>
<p style="margin-left: 4rem;">
    Ein vom Partner vermittelter Kunde (z.B. durch Verwendung eines Werbelinks
    oder Hoplinks) investiert erstmalig und endgültig in ein Finanzprodukt gemäß
    Ziffer 2. des Anhangs "Provisionsvereinbarungen" (Kunden, die auf der
    Plattform investieren, werden nachfolgend auch als „<strong>Investor</strong>“ bezeichnet)
    (nachfolgend auch das "<strong>Erstinvestment</strong>"). Das Investment
    eines Investors gilt dann als endgültig abgeschlossen, wenn Exporo den Investor innerhalb einer
    Frist von acht Wochen beginnend mit der vollständig abgeschlossenen
    Registrierung nicht abgelehnt hat, der vom Partner vermittelte Investor
    sein Investment auf das entsprechende Konto eingezahlt und über die
    Plattform nicht innerhalb der gesetzlichen Widerrufsfrist von 14-Tagen
    wirksam widerrufen hat ("<strong>Endgültiges Investment</strong>").
</p>
<p style="margin-left: 4rem;">
    <strong>iii. Vergütungsanspruch Folgeinvestment</strong>
</p>
<p style="margin-left: 4rem;">
    Ein Investor nimmt binnen {{ $contract->claim_years }} Jahren nach seiner
    Registrierung bei Exporo und seinem Erstinvestment ein weiteres Endgültiges
    Investment in ein Finanzprodukt gemäß Ziffer 2. des Anhangs
    "Provisionsvereinbarungen" bei Exporo vor ("<strong>Folgeinvestment</strong>").
</p>
<p style="margin-left: 4rem;">
    <strong>iv. Vergütungsanspruch Eigeninvestment</strong>
</p>
<p style="margin-left: 4rem;">
    Der Vergütungsanspruch des Partners gemäß vorstehendem ii. und iii.
    erstreckt sich auch auf Endgültige Investments, die der Partner selbst im
    eigenen Namen als sog. Eigeninvestment vornimmt. Dieser Vergütungsanspruch
    für ein Eigeninvestment entfällt jedoch, wenn der Partner über sein
    Eigeninvestment hinaus zuvor keinen weiteren Vergütungsanspruch gemäß dieser
    Ziffer 2. durch ein Engültiges Investment eines Investors, der von ihm
    selbst personenverschieden ist, erworben hat. Die Beurteilung, ob
    Personenverschiedenheit vorliegt, liegt im alleinigen Ermessen von Exporo.)
</p>
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>b. Entfallen des Vergütungsanspruchs</strong>
</p>
<p style="margin-left: 2rem;">
    Dem Partner ist bekannt, dass Exporo die Registrierung eines potentiellen
    Investors bzw. die Vermittlung eines Investments mit dem Investor über die
    Plattform ablehnen kann. Der Partner hat insoweit keinen Anspruch auf die
    Registrierung oder die Durchführung des Investments sowie deren Provision.
    Eine Ablehnung durch Exporo kann insbesondere in folgenden Konstellationen
    erfolgen, ohne dass die Aufzählung abschließend wäre:
</p>
<p style="margin-left: 4rem;">
    <strong>i.</strong> Der potentielle Investor ist nicht volljährig oder sonst nicht
    geschäftsfähig;
</p>
<p style="margin-left: 4rem;">
    <strong>ii.</strong> der potentielle Investor ist keine natürliche oder juristische Person
    (zum Beispiel bei Registrierungen über automatische Scripts/Bots oder
    ähnlich);
</p>
<p style="margin-left: 4rem;">
    <strong>iii.</strong> der potentielle Investor ist bereits registrierter Nutzer bzw.
    bestehender Investor von Exporo oder Unternehmen der Exporo Gruppe
    (vorbehaltlich vorstehende Ziffer 2. a. v. findet Anwendung);
</p>
<p style="margin-left: 4rem;">
    <strong>iv.</strong> es bestehen Anhaltspunkte dafür, dass der potentielle Investor
    tatsächlich nicht existiert oder tatsächlich kein Aufbau einer
    Geschäftsbeziehung beabsichtigt ist (z.B. bei der Verwendung sog.
    Fake-Accounts, der Verwendung sog. „Wegwerf-Emailadressen“ oder der Eingabe
    nicht sinnvoller Daten in das Anmeldeformular).
</p>
<p style="margin-left: 2rem;">
    Exporo weist den Partner hiermit darauf hin, dass es den
    Kooperationspartnern ebenfalls freisteht, das Investment eines potentiellen
    Investors abzulehnen.
</p>
<p style="margin-left: 2rem;">
    Sollte ein potentieller Investor bei der Registrierung keinem Tippgeber
    zugeordnet sein, besteht bei nachträglicher Zuordnung des potentiellen
    Investors zu dem Partner seitens des Partners kein Anspruch auf eine
    Vergütung von Ansprüchen, die in der Vergangenheit liegen.
</p>
<p style="margin-left: 2rem;">
    Der Vergütungsanspruch des Partners entfällt ferner, wenn ein Erst- oder
    Folgeinvestment rückabgewickelt wird oder von Anfang an unwirksam ist.
    Bereits geleistete Vergütungen sind in diesen Fällen an Exporo
    zurückzuzahlen.
</p>
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>c. Berechnung und Höhe der Vergütung</strong>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>i.</strong> Die Höhe der Vergütung für eine Registrierung gemäß
        2. a) i. ergibt sich aus Ziffer 3. des Anhangs "Provisionsvereinbarungen".
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>ii.</strong> Im Fall einer Vergütung von Erst- oder
        Folgeinvestments ist für die Berechnung der Vergütung der in Ziffer 2.
        des Anhangs "Provisionsvereinbarungen" genannte prozentuale Faktor
        heranzuziehen und auf die konkrete Höhe des Investments zu berechnen.
        Der Wert wird grundsätzlich auf eine Laufzeit des Finanzproduktes von
        24 Monaten berechnet. Sollte ein Finanzprodukt mit einer Laufzeit von
        weniger als 24 Monaten von einem Investor gezeichnet werden, so berechnet
        sich die Vergütung anteilig auf die kürzere Laufzeit, da Exporo von den
        Kooperationspartnern grundsätzlich eine deutlich geringere Vergütung bei
        kurzen Laufzeiten der Finanzprodukte erhält. Bei einer Laufzeit von 18 
        Monaten und einem Provisionsfaktor von 2,0 % zum Beispiel berechnet sich
        die Vergütung wie folgt: 18 dividiert durch 24 und multipliziert mit dem
        Provisionsfaktor von 2,0 % auf die Höhe des endgültigen Investments
        ergibt eine Vergütung des Partners von 1,5 % bezogen auf die Höhe des
        konkreten Investments. Hat ein Finanzprodukt eine längere Laufzeit als
        24 Monate, erhöht sich die Vergütung des Partners nicht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iii.</strong> Sofern einem durch den Partner namhaft gemachten
        Kunden Vorzugskonditionen für den Abschluss eines Investments angeboten
        werden, unabhängig davon von welcher Partei die Vorzugskonditionen
        angeboten werden, ist Exporo dazu berechtigt, die Vorzugskonditionen
        anteilig oder in voller Höhe von der Vergütung des Partners abzuziehen.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iv.</strong> Sieht sich Exporo aus Markt- oder Wettbewerbsgründen
        veranlasst, in Einzelfällen eine ungewöhnlich niedrige eigene Vergütung
        bei einem Geschäft von seinem Kooperationspartner zu erhalten, so kann
        die dem Partner für diese Geschäfte zustehende Vergütung gekürzt werden.
        Der Gesamtbetrag aus der Vergütung für die Namhaftmachung von potentiellen
        Investoren gemäß Ziffer 2. des Anhangs "Provisionsvereinbarungen" wird
        hiermit beschränkt auf einen Betrag in Höhe von maximal 50% derjenigen
        Vergütung, die Exporo selbst für das jeweilige Geschäft von seinem
        Kooperationspartner zusteht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>v.</strong> Mit der Vergütung gemäß des Anhangs
        „Provisionsvereinbarungen" zu dieser Vergütungsvereinbarung ist die
        Tätigkeit des Partners im Rahmen des Partnerprogramms vollumfänglich
        abgegolten; weitergehende Vergütungs- oder Aufwendungsersatzansprüche
        bestehen nicht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>vi.</strong> Die Aufrechnung mit gegenüber Exporo bestehenden
        Forderungen oder die Zurückbehaltung von Zahlungen wegen solcher
        Ansprüche ist nur zulässig, soweit die Gegenansprüche unbestritten,
        entscheidungsreif oder rechtskräftig festgestellt sind.
    </span>
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>d. Namhaftmachung von potentiellen Sub-Partnern</strong>
</p>
<p style="margin-left: 2rem;">
    <span>
        Macht der Partner gegenüber Exporo einen Sub-Partner namhaft und nimmt
        der Sub-Partner danach selbst als Tippgeber an dem Partnerprogramm gemäß
        Ziffer 1. teil, so erwirbt der Partner gegenüber Exporo jeweils einen
        Overhead-Partner Vergütungsanspruch für die in Ziffer 2. a) ii & iii
        genannten Fälle. Die Höhe des jeweiligen Overhead-Partner
        Vergütungsanspruchs richtet sich gemäß Ziffer 3. des Anhangs
        "Provisionsvereinbarungen".
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>i.</strong> Der Anspruch des Partners auf Overhead-Partner Vergütung besteht nur
        dann, wenn der Sub-Partner selbst einen Anspruch auf Vergütung hat.
        Sollte der namhaftgemachte Sub-Partner die Zusammenarbeit beenden oder
        aus sonstigen Gründen keine Provision erhalten, erhält auch der Partner
        keine Overhead-Partner Vergütung nach dieser Vergütungsvereinbarung.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>ii.</strong> Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen
        eine ungewöhnlich niedrige eigene Vergütung aus einem Projekt mit seinen
        Kooperationspartnern zu akzeptieren, so kann die dem Partner für diese
        Geschäfte zustehende Vergütung angemessen gekürzt werden. Namentlich
        soll der Gesamtbetrag aus der Vergütung für die Namhaftmachung von
        potentiellen Investoren gemäß Ziffer 2. des Anhangs
        "Provisionsvereinbarungen" und der Vergütung für die Namhaftmachung von
        potenziellen Sub-Partnern gemäß Ziffer 3. des Anhangs
        "Provisionsvereinbarungen". beschränkt werden auf einen Betrag in Höhe
        von maximal 50% derjenigen Vergütung, die Exporo für das jeweilige
        Geschäft von seinem Kooperationspartner zusteht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iii.</strong> Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen
        mit einem Sub-Partner eine zusätzliche Vergütung zu vereinbaren, so kann
        die dem Partner für diese Geschäfte zustehende Vergütung ebenfalls
        angemessen gekürzt werden. Die Kürzung erfolgt über eine (ggf. anteilige)
        Reduzierung der Höhe der Overhead-Partner Vergütung gemäß Ziffer 3. des
        Anhangs "Provisionsvereinbarungen".
    </span>
</p>
@endif

<h3><strong>3. Fälligkeit / Abrechnung der Vergütung</strong></h3>
<p style="margin-left: 2rem;">
    a. Exporo erteilt dem Partner monatlich zum 20. eines Monats eine Abrechnung
    über die im jeweiligen Vormonat entstandenen Vergütungen. Sollte der 20. auf
    einen Sonn- oder Feiertag fallen, so verschiebt sich die Abrechnung auf den
    folgenden Werktag. Die Abrechnung wird im Partner Cockpit hinterlegt. Die
    Vergütung wird jeweils einem Vergütungskonto gutgeschrieben. Das Guthaben
    des Vergütungskontos wird von Exporo jeweils bis zum Ende des Kalendermonats
    per Banküberweisung auf die im Cockpit hinterlegte Bankverbindung ausgezahlt.
</p>
<p style="margin-left: 2rem;">
    b. Widerspricht der Partner einer Abrechnung nicht binnen einer Frist von 4
    (vier) Wochen nach Erhalt der jeweiligen Abrechnung, so gilt diese Abrechnung
    als ausdrücklich anerkannt. Exporo ist darüber hinaus berechtigt, den Partner
    in regelmäßigen Abständen zur Anerkenntnis der erteilten Abrechnungen über
    die Vergütung aufzufordern.
</p>
<h3><strong>4. Versteuerung der Einkünfte</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a. Für sämtliche gewerbliche oder behördliche Erlaubnispflichten bzw.
        Zulassungen, die sich aus der Tätigkeit des Partners ergeben, ist der
        Partner selbst zuständig. Der Partner erklärt, dass er seinen
        Verpflichtungen insoweit nachkommt und gegenüber Exporo hierüber auf
        Anforderung einen entsprechenden Nachweis erbringen kann (z.B.
        Gewerbeanmeldung, Ust.-IdNr.),
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b. Für den Fall, dass die seitens Exporo gezahlten Vergütungen entgegen
        der §§ 4 Nr. 8 lit. F und 11 UstG (1999) von der Finanzverwaltung als
        umsatzsteuerpflichtig bewertet werden sollten, vereinbaren die
        Vertragsparteien ausdrücklich, dass diese Umsatzsteuer bereits in der
        Vergütung gemäß des Anhangs "Provisionsvereinbarungen" enthalten ist.
        Der Ausweis der gesetzlichen Ust. erfolgt erst ab dem Monat, in dem die
        Mitteilung darüber eingegangen ist. Eine wegen verspäteter Mitteilung
        rückwirkende Korrektur von bereits ohne Ust. ausgestellter Gutschriften
        erfolgt nicht.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c. Als selbstständiger Tippgeber ist der Partner für die Einhaltung der
        rechtmäßigen Steuerzahlungen zu der Vergütung gemäß des Anhangs
        "Provisionsvereinbarungen" und der Meldung an sein zuständiges Finanzamt
        als auch für die sozialversicherungsrechtlichen Bestimmungen selbst
        verantwortlich.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        d. Sofern der Ausweis der Ust. beantragt wird, erfolgt die Erhebung
        grundsätzlich für jede Einzelbuchung. Die Darstellung erfolgt aufsummiert.
    </span>
</p>

<h3><strong>5. Laufzeit und Beendigung, Vertragsänderungen</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a. Diese Vereinbarung tritt mit Unterzeichnung in Kraft und wird auf
        unbestimmte Zeit geschlossen.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b. Beide Parteien sind berechtigt, diese Vereinbarung mit einer Frist
        von {{ $contract->cancellation_days }} Tag(en) zu kündigen. Das Recht
        zur außerordentlichen Kündigung aus wichtigem Grund bleibt hiervon
        unberührt.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c. Der Partner ist berechtigt, einen Antrag auf Änderung seines
        Provisionsschemas gemäß Ziffer 3 des Anhangs "Provisionsvereinbarungen"
        zu stellen. Der Antrag kann entweder über seinen direkten Ansprechpartner
        erfolgen oder über <a
            href="mailto:abrechnung@exporo.com."
            >abrechnung@exporo.com.</a>. Sofern der Änderung zugestimmt
        wird, wird dem Partner in seinem Cockpit der neue Anhang zur Verfügung
        gestellt.
    </span>
</p>
<h3><strong>6. Sondervereinbarung / Nebenabreden</strong></h3>
<p style="margin-left: 2rem;">
    @empty($contract->special_agreement)
        Nebenabreden zu diesem Vertrag sind nicht getroffen.
    @else
        {{ $contract->special_agreement }}
    @endempty
</p>
<p><br /></p>
<h3><strong>7. Schlussbestimmungen</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a. Änderungen oder Ergänzungen dieses Vertrages, einschließlich dieser
        Schriftformklausel, bedürfen zu ihrer Wirksamkeit der Schriftform. Gleiches
        gilt für Neben- und Zusatzabreden.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b. Diese Vereinbarung einschließlich ihrer Anlagen und das
        Rechtsverhältnis zwischen Exporo und dem Partner unterliegen dem Recht
        der Bundesrepublik Deutschland.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c. Wenn der Partner Unternehmer ist, sind Erfüllungsort für alle
        Leistungen und Gerichtsstand für sämtliche Auseinandersetzungen aus und
        im Zusammenhang mit diesem Vertrag, soweit dies gesetzlich zulässig ist,
        Hamburg. Dies gilt auch dann, wenn der Partner keinen allgemeinen
        Gerichtsstand im Inland hat. Exporo behält sich das Recht vor, den
        Partner an seinem allgemeinen Gerichtsstand zu verklagen. Gesetzliche
        Regelungen über ausschließliche Zuständigkeiten bleiben unberührt.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        d. Sollten sich einzelne Bestimmungen dieser Vereinbarung als ungültig
        oder undurchführbar erweisen, so wird dadurch die Gültigkeit der
        Vereinbarung im Übrigen nicht berührt. In einem solchen Fall sind die
        Parteien verpflichtet, die ungültige oder undurchführbare Bestimmung
        durch diejenige gesetzlich zulässige Bestimmung zu ersetzen, die den
        Zweck der ungültigen oder undurchführbaren Bestimmung, insbesondere das,
        was die Parteien gewollt haben, mit der weitestgehend möglichen
        Annäherung erreicht. Entsprechendes gilt, wenn sich bei Durchführung der
        Vereinbarung eine ergänzungsbedürftige Lücke ergeben sollte.
    </span>
</p>

<table style="min-width: 50vw; margin-top: 3rem">
    <tr>
        <td>
            <div style="border-bottom: 1px solid black">
                @if($contract->accepted_at !== null)
                {{ $contract->user->company->city }},
                {{ optional($contract->released_at)->format('d.m.Y') }}
                @endif
            </div>
            <p style="margin-top: 0.25rem">Ort, Datum</p>
        </td>
        <td style="width: 3rem"></td>
        <td style="width: 50%; vertical-align: bottom">
            <div style="border-bottom: 1px solid black">
                @if($contract->accepted_at !== null)
                {{ $contract->user->details->address_city }},
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
        <td style=" vertical-align: bottom">
            <div style="border-bottom: 1px solid black">
                <img
                    src="{{ url('/images/unterschrift-exporo.png') }}"
                    alt="Unterschrift Exporo"
                    style="width: 50%"
                >
            </div>
            <p style="margin-top: 0.25rem">Exporo Investment GmbH</p>
        </td>
        <td style="width: 3rem"></td>
        <td style="width: 50%; vertical-align: bottom">
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
