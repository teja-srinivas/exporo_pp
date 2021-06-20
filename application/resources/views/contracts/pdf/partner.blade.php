@extends('contracts.pdf.layout')

@section('content')
<h1 style="text-align: center"><strong>Tippgebervereinbarung</strong></h1>
<p style="text-align: center;">zwischen</p>
<p>
    der Exporo Investment GmbH,<br>
    Am Sandtorkai 70, 20457 Hamburg, HRB Hamburg 146341,
    vertreten durch ihren Prokuristen Patrick Hartmann.
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
    Exporo betreibt unter anderem die Webseiten <a href="https://exporo.de">https://exporo.de</a> und <a href="https://www.propvest.de">https://www.propvest.de</a> (nachfolgend auch „Plattform“ genannt),
    über die Anleger in Immobilien und Immobilienprojekte investieren sowie Unternehmen (nachfolgend auch Projektentwickler" genannt)
    Kapital für Immobilien und Immobilienprojekte einwerben können.
</p>
<p>
    <br />
    Exporo hat darüber hinaus eine Webseite unter <a href="https://partnerprogramm.exporo.de">https://partnerprogramm.exporo.de</a> eingerichtet, über die sich Dritte als Tippgeber
    von Exporo registrieren und u.a. mit den von Exporo auf der Webseite für die Tippgeber zur Verfügung gestellten Werbematerialien
    potentielle Anleger auf die Plattform oder Exporo hinweisen können. Für den Fall, dass der Tippgeber erfolgreich Anleger namhaft
    macht, die in Produkte von Exporo investieren, beabsichtigt Exporo dem Tippgeber unter bestimmten Voraussetzungen eine Provision
    zu zahlen ("Partnerprogramm"). Der Partner ist daran interessiert, als Tippgeber im Sinne dieser Vereinbarung tätig zu werden.
    Dies vorausgeschickt vereinbaren Exporo und der Partner folgende Vereinbarung:
</p>
<h3><strong>1. Vertragsgegenstand</strong></h3>
<p style="margin-left: 2rem;">
    Der Partner ist berechtigt, aber nicht verpflichtet, im Rahmen des Partnerprogramms als Tippgeber tätig zu werden.
</p>
<p style="margin-left: 2rem;">
    Gegenstand dieser Vereinbarung sind auch die "Allgemeinen Geschäftsbedingungen für die Teilnahme am Exporo Partnerprogramm"
    (nachfolgend auch "AGB" genannt) in der jeweils aktuellen Fassung sowie der „Anhang Provisionsvereinbarung“.
    Bei etwaigen Widersprüchen zwischen dieser Tippgebervereinbarung und den AGB gehen die Bestimmungen dieser Tippgebervereinbarung den AGB vor.
</p>
<p style="margin-left: 2rem;">
    Die Tätigkeit des Tippgebers erstreckt sich auf die gelegentliche Namhaftmachung der Plattform oder von Exporo bzw.
    Gesellschaften der Exporo Gruppe gegenüber potentiellen Anlegern. Die Namhaftmachung durch den Tippgeber erfolgt in
    der Regel durch die Verwendung von den von Exporo im Partnercockpit gemäß § 2 der AGB hinterlegten Werbematerialien
    wie z.B. Banner, Produktdaten, Text-Links, E-Mails, Videos etc. mit integrierten Hoplinks, indem der Partner diese
    auf einer von ihm betriebenen Homepage oder Website oder einen eigenen Blog veröffentlich oder einer E-Mail an potentielle
    Anleger versendet ("Namhaftmachung"). Der Anleger kann sich über einen von dem Partner zur Verfügung gestellten Werbelink
    oder Hoplink erstmals auf der Seite <a href="https://exporo.de">https://exporo.de</a>,
    <a href="https://www.propvest.de">https://www.propvest.de</a> bzw. einer anderen von Exporo betriebenen Landingpage
    (inkl. Double Opt-in, nachfolgend „DOI“) als Nutzer registrieren.
</p>
<p style="margin-left: 2rem;">
    Soweit der Partner auf der Plattform gelistete Finanzprodukte im Rahmen seiner Tätigkeit als Tippgeber vergleicht,
    darf sich dieser Vergleich nur auf wenige Merkmale der Finanzprodukte (z.B. Zins und Produkttyp) erstrecken.
    Die vollständige Produktvorstellung ist nur über die Plattform zulässig. Daher ist die Nennung von Exporo als Betreiber der
    Plattform erwünscht, nicht aber die Nennung des Emittenten. Es muss zudem klar erkennbar sein, dass es sich hierbei nicht um
    ein Angebot handelt, welches der Partner anbietet oder vermittelt. Es muss aus der Darstellung hervorgehen, dass die Produkte
    über die Plattform angeboten werden. Dem Partner ist es untersagt, bewusst und final auf einen potentiellen Anleger einzuwirken,
    damit dieser ein Geschäft über die Anschaffung oder über die Veräußerung von Finanzinstrumenten abschließt.
</p>
<p style="margin-left: 2rem;">
    Der Partner verpflichtet sich ausdrücklich, als Tippgeber keine Beratung von potentiellen Anlegern in Bezug auf
    Investmentmöglichkeiten vorzunehmen und gegenüber den potentiellen Anlegern nicht auf einen Vertragsschluss hinzuwirken.
    Einzelne Finanzprodukte von Exporo werden durch den Tippgeber nicht unmittelbar angeboten oder vermittelt.
    Der Tippgeber wird keine Anträge der Anleger aufnehmen.
</p>
<p style="margin-left: 2rem;">
    Für den Partner ist die Teilnahme als Tippgeber am Partnerprogramm gegenüber Exporo kostenfrei.
</p>
<p style="margin-left: 2rem;">
    Mit Wirksamwerden dieser Vereinbarung werden alle etwaigen früheren Vereinbarungen über die Teilnahme am Partnerprogramm
    zwischen den Parteien oder dem Partner und der Exporo AG durch diese Vereinbarung ersetzt.
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;">
    Ergänzend ist es dem Partner gestattet, gegenüber Exporo seinerseits potentielle Tippgeber namhaft zu machen und
    diese Exporo als neue Partner zuzuführen (nachfolgend „<strong>Sub-Partner</strong>“). Für die Zuführung von Sub-Partnern erhält
    der Partner eine gesonderte Vergütung gemäß Ziffer 2. des Anhangs "Provisionsvereinbarungen". Exporo ist berechtigt,
    die Aufnahme der namhaft gemachten Sub-Partner ohne Angabe von Gründen abzulehnen. Exporo weist den Partner vorsorglich
    darauf hin, dass es etwaigen Sub-Partnern nicht gestattet ist, ebenfalls neue Partner als Sub-Sub-Partner namhaft zu machen.
    Vielmehr beschränkt sich die Tätigkeit der Sub-Partner auf die Namhaftmachung neuer potentieller Investoren.)
</p>
@endif

@if($contract->is_exclusive)
<p style="margin-left: 2rem;">
    Der Partner ist während der Laufzeit dieses Vertrages dergestalt exklusiv für Exporo tätig, dass er in Bezug auf digitale
    Immobilieninvestments ausschließlich die Plattform bzw. Exporo gegenüber potentiellen Investoren namhaft macht.)
</p>
@endif

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
        Der Partner erhält von Exporo eine erfolgsabhängige Vergütung (nachfolgend
        "Provision" oder "Vergütung" genannt) gemäß den nachfolgenden Bestimmungen:
    </span>
</p>
<p style="margin-left: 4rem;">
    <strong>i.	Vergütungsanspruch Erstinvestment</strong>
</p>
<p style="margin-left: 4rem;">
    Der Partner erhält eine Provison für ein Erstinvestment eines namhaft gemachten Anlegers.
    Ein vergütungspflichtiges Erstinvestment liegt vor, wenn ein Anleger aufgrund der seitens
    des Partners erfolgten Namhaftmachung der Plattform (z.B. durch Verwendung eines Werbelinks
    oder Hoplinks) erstmalig und endgültig in ein Finanzprodukt gemäß Ziffer 2. des Anhangs
    "Provisionsvereinbarungen" investiert. Das Investment eines Anlegers gilt dann als endgültig
    abgeschlossen, wenn a) Exporo den Anleger innerhalb einer Frist von acht Wochen, beginnend
    mit der vollständig abgeschlossenen Registrierung, nicht abgelehnt hat, b) der vom Partner
    namhaft gemachte Anleger sein Investment auf das entsprechende Konto eingezahlt hat und c)
    der Anleger das Investment nicht innerhalb der gesetzlichen Widerrufsfrist
    von 14-Tagen wirksam widerrufen hat „(endgültiges Erstinvestment").
</p>
<p style="margin-left: 4rem;">
    <strong>ii. Vergütungsanspruch Folgeinvestment</strong>
</p>
<p style="margin-left: 4rem;">
    Der Partner erhält zudem Provisionen für Folgeinvestments. Tätigt ein Anleger, der ein
    vorstehend definiertes Erstinvestment abgeschlossen hat, innerhalb eines Zeitraums von
    {{ $contract->claim_years }} Jahren nach Abschluss des ersten Investments weitere
    Investments, steht dem Partner für diese Investments (Folgeinvestments) ebenfalls jeweils ein Vergütungsanspruch zu.
</p>
<p style="margin-left: 4rem;">
    Ein Anspruch auf Vergütung von Folgeinvestments besteht nach Beendigung des Vertrages für die
    Dauer einer Nachfrist von zwei Jahren. Beruht die Beendigung des Vertrages jedoch auf eine
    berechtigte außerordentliche Kündigung von Exporo, besteht kein Vergütungsanspruch. Erfolgt die
    Beendigung des Vertrages aufgrund einer ordentlichen Kündigung des Partners, verkürzt sich die
    Nachfrist auf ein Jahr. Im Falle einer berechtigten außerordentlichen Kündigung seitens des Partners
    oder einer ordentlichen Kündigung seitens Exporo verbleibt es bei der zweijährigen Nachfrist.
</p>
<p style="margin-left: 4rem;">
    <strong>iii. Vergütungsanspruch Registrierung</strong>
</p>
<p style="margin-left: 4rem;">
    Der Partner erhält weiter eine Provison, wenn ein potentieller Anleger sich über einen von dem
    Partner zur Verfügung gestellten Werbelink oder Hoplink erstmals auf der Seite  <a href="https://exporo.de">https://exporo.de</a> bzw.
    einer anderen von Exporo betriebenen Landingpage (inkl. DoubleOpt-in) als Nutzer registriert.
    Die Höhe der Provision ergibt sich aus dem Anhang "Provisionsvereinbarungen".
</p>
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>b. Kein Vergütungsanspruch bei Ablehnung des Anlegers</strong>
</p>
<p style="margin-left: 2rem;">
    Die Parteien sind sich darüber einig, dass Exporo die Registrierung eines potentiellen Anlegers bzw.
    den Abschluss eines Investments mit dem Anleger über die Plattform ablehnen kann. Der Partner hat also
    keinen Anspruch auf die Registrierung oder die Durchführung des Investments und folglich -
    bei einer Ablehnung - auch keinen Provisionsanspruch. Eine Ablehnung durch Exporo kann insbesondere,
    aber nicht ausschließlich, in folgenden Fällen erfolgen:
</p>
<p style="margin-left: 4rem;">
    <strong>i.</strong> Der potentielle Anleger ist nicht volljährig oder sonst nicht geschäftsfähig;
</p>
<p style="margin-left: 4rem;">
    <strong>ii.</strong> der potentielle Anleger ist keine natürliche oder juristische Person
    (zum Beispiel bei Registrierungen über automatische Scripts/Bots oder ähnlich);
</p>
<p style="margin-left: 4rem;">
    <strong>iii.</strong> der potentielle Anleger ist bereits registrierter Nutzer bzw.
    bestehender Anleger von Exporo oder Unternehmen der Exporo Gruppe
    (vorbehaltlich vorstehende Ziffer 2. a. v. findet Anwendung);
</p>
<p style="margin-left: 4rem;">
    <strong>iv.</strong> es bestehen Anhaltspunkte dafür, dass der potentielle Anleger tatsächlich
    nicht existiert oder tatsächlich kein Aufbau einer Geschäftsbeziehung beabsichtigt ist
    (z.B. bei der Verwendung sog. Fake-Accounts, der Verwendung sog. „Wegwerf-Emailadressen“ oder
    der Eingabe nicht sinnvoller Daten in das Anmeldeformular).
</p>
<p style="margin-left: 2rem;">
    Exporo weist den Partner hiermit darauf hin, dass es den Projektentwicklern ebenfalls freisteht,
    das Investment eines potentiellen Anlegers abzulehnen. Auch in solchen Fällen, entsteht kein Provisionsanspruch für den Partner.
</p>
<p style="margin-left: 2rem;">
    Der Vergütungsanspruch des Partners entfällt ferner, wenn ein Erst- oder Folgeinvestment rückabgewickelt
    wird oder von Anfang an unwirksam ist. Bereits geleistete Vergütungen sind in diesen Fällen an Exporo zurückzuzahlen.
</p>
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>c. Berechnung und Höhe der Vergütung</strong>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>i.</strong> Im Fall einer Vergütung von Erst- oder Folgeinvestments
        ist für die Berechnung der Vergütung der in Ziffer 2. des Anhangs "Provisionsvereinbarungen"
        genannte prozentuale Faktor heranzuziehen und auf die konkrete Höhe des Investments zu berechnen.
        Der Wert wird grundsätzlich auf eine Laufzeit des Finanzproduktes von 24 Monaten berechnet.
        Sollte ein Finanzprodukt mit einer Laufzeit von weniger als 24 Monaten von einem Anleger gezeichnet werden,
        so berechnet sich die Vergütung anteilig auf die kürzere Laufzeit, da Exporo von den Projektentwicklern
        grundsätzlich eine deutlich geringere Vergütung bei kurzen Laufzeiten der Finanzprodukte erhält.
        Bei einer Laufzeit von 18 Monaten und einem Provisionsfaktor von 2,0 % zum Beispiel berechnet sich
        die Vergütung wie folgt: 18 dividiert durch 24 und multipliziert mit dem Provisionsfaktor von 2,0 %
        auf die Höhe des endgültigen Investments. Dies ergibt eine Vergütung des Partners von 1,5 % bezogen
        auf die Höhe des konkreten Investments. Hat ein Finanzprodukt eine längere Laufzeit als 24 Monate,
        erhöht sich die Vergütung des Partners nicht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>ii.</strong> Sofern einem durch den Partner namhaft gemachten Kunden Vorzugskonditionen
        (Gutscheincode, Coupon, prozentuale oder absolute Boni/Nachlässe etc.) für den Abschluss eines
        Investments angeboten werden, unabhängig davon von welcher Partei die Vorzugskonditionen angeboten
        werden, ist Exporo dazu berechtigt, die Vorzugskonditionen anteilig oder in voller Höhe
        von der Vergütung des Partners abzuziehen.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iii.</strong> Sieht sich Exporo aus Markt- oder Wettbewerbsgründen veranlasst, in Einzelfällen
        eine ungewöhnlich niedrige eigene Vergütung bei einem Geschäft von seinem Projektentwickler zu erhalten
        (ab unter 4 %), so kann die dem Partner für diese Geschäfte zustehende Vergütung auf  50% derjenigen
        Vergütung, die Exporo selbst für das jeweilige Geschäft von dem Projektentwickler beanspruchen kann,
        gekürzt werden.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iv.</strong> Mit der Vergütung gemäß dem Anhang „Provisionsvereinbarungen" zu dieser
        Tippgebervereinbarung ist die Tätigkeit des Partners im Rahmen des Partnerprogramms vollumfänglich
        abgegolten; weitergehende Vergütungs- oder Aufwendungsersatzansprüche bestehen nicht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>v.</strong> Die Aufrechnung mit gegenüber Exporo bestehenden Forderungen oder die Zurückbehaltung
        von Zahlungen wegen solcher Ansprüche ist nur zulässig, soweit die Gegenansprüche unbestritten, entscheidungsreif
        oder rechtskräftig festgestellt sind.
    </span>
</p>

@if($contract->allow_overhead)
<p style="margin-left: 2rem;"><br /></p>
<p style="margin-left: 2rem;">
    <strong>d. Namhaftmachung von potentiellen Sub-Partnern</strong>
</p>
<p style="margin-left: 2rem;">
    <span>
        Macht der Partner gegenüber Exporo einen Sub-Partner namhaft und nimmt der Sub-Partner danach selbst
        als Tippgeber an dem Partnerprogramm gemäß Ziffer 1. teil, so erwirbt der Partner gegenüber Exporo
        jeweils einen Overhead-Partner Vergütungsanspruch für die in Ziffer 2. a) ii & iii genannten Fälle.
        Die Höhe des jeweiligen Overhead-Partner Vergütungsanspruchs richtet sich gemäß Ziffer 3. des
        Anhangs "Provisionsvereinbarungen".
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>i.</strong> Der Anspruch des Partners auf Overhead-Partner Vergütung besteht nur dann,
        wenn der Sub-Partner selbst einen Anspruch auf Vergütung hat. Sollte der namhaftgemachte Sub-Partner
        die Zusammenarbeit beenden oder aus sonstigen Gründen keine Provision erhalten, erhält auch der Partner
        keine Overhead-Partner Vergütung nach dieser Vergütungsvereinbarung.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>ii.</strong> Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen eine
        ungewöhnlich niedrige eigene Vergütung aus einem Projekt mit seinen Kooperationspartnern zu akzeptieren,
        so kann die dem Partner für diese Geschäfte zustehende Vergütung angemessen gekürzt werden. Namentlich
        soll der Gesamtbetrag aus der Vergütung für die Namhaftmachung von potentiellen Investoren gemäß Ziffer
        2. des Anhangs "Provisionsvereinbarungen" und der Vergütung für die Namhaftmachung von potenziellen
        Sub-Partnern gemäß Ziffer 3. des Anhangs "Provisionsvereinbarungen". beschränkt werden auf einen Betrag
        in Höhe von maximal 50% derjenigen Vergütung, die Exporo für das jeweilige Geschäft von seinem Kooperationspartner zusteht.
    </span>
</p>
<p style="margin-left: 4rem;">
    <span>
        <strong>iii.</strong> Sieht sich Exporo aus Wettbewerbsgründen veranlasst, in Einzelfällen mit einem Sub-Partner
        eine zusätzliche Vergütung zu vereinbaren, so kann die dem Partner für diese Geschäfte zustehende Vergütung ebenfalls
        angemessen gekürzt werden. Die Kürzung erfolgt über eine (ggf. anteilige) Reduzierung der Höhe der Overhead-Partner
        Vergütung gemäß Ziffer 3. des Anhangs "Provisionsvereinbarungen".)
    </span>
</p>
@endif

<h3><strong>3. Fälligkeit / Abrechnung der Vergütung</strong></h3>
<p style="margin-left: 2rem;">
    a. Exporo erteilt dem Partner monatlich spätestens bis zum 20. eines Monats eine Abrechnung über die  im jeweiligen Vormonat
    entstandenen Vergütungen. Sollte der 20. auf einen Sonn- oder Feiertag fallen, so verschiebt sich die Abrechnung auf den
    folgenden Werktag. Die Abrechnung wird im Partner Cockpit hinterlegt. Die Vergütung wird jeweils einem Vergütungskonto gutgeschrieben.
    Das Guthaben des Vergütungskontos wird von Exporo jeweils bis zum Ende des Kalendermonats per Banküberweisung auf die im
    Cockpit hinterlegte Bankverbindung ausgezahlt.
</p>
<p style="margin-left: 2rem;">
    b. Widerspricht der Partner einer Abrechnung nicht binnen einer Frist von 4 (vier) Wochen nach Erhalt der jeweiligen Abrechnung,
    so gilt diese Abrechnung als ausdrücklich anerkannt. Exporo ist darüber hin- aus berechtigt, den Partner in regelmäßigen Abständen
    zum Anerkennen der erteilten Abrechnungen über die Vergütung aufzufordern.
</p>
<h3><strong>4. Versteuerung der Einkünfte</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a. Für sämtliche gewerbliche oder behördliche Erlaubnispflichten bzw. Zulassungen, die sich aus der Tätigkeit des Partners ergeben,
        ist der Partner selbst zuständig. Der Partner erklärt, dass er seinen Verpflichtungen insoweit nachkommt und gegenüber Exporo hierüber
        auf Anforderung einen entsprechenden Nachweis erbringen kann (z.B. Gewerbeanmeldung, USt.-IdNr.).
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b. Für den Fall, dass die seitens Exporo gezahlten Vergütungen entgegen der §§ 4 Nr. 8 lit. F und 11 UStG (1999) von der
        Finanzverwaltung als umsatzsteuerpflichtig bewertet werden sollten, vereinbaren die Vertragsparteien ausdrücklich,
        dass diese Umsatzsteuer bereits in der Vergütung gemäß des Anhangs "Provisionsvereinbarungen" enthalten ist.
        Der Ausweis der gesetzlichen USt. erfolgt erst ab dem Monat, in dem die Mitteilung darüber eingegangen ist.
        Eine wegen verspäteter Mitteilung rückwirkende Korrektur von bereits ohne USt. ausgestellter Gutschriften erfolgt nicht.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c. Als selbstständiger Tippgeber ist der Partner für die Einhaltung der rechtmäßigen Steuerzahlungen zu der Vergütung
        gemäß des Anhangs "Provisionsvereinbarung" und der Meldung an sein zuständiges Finanzamt als auch für die
        sozialversicherungsrechtlichen Bestimmungen selbst verantwortlich.
    </span>
</p>

<h3><strong>5. Laufzeit und Kündigungsfrist </strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a. Diese Vereinbarung tritt mit Unterzeichnung in Kraft und wird auf unbestimmte Zeit geschlossen.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b. Beide Parteien sind berechtigt, diese Vereinbarung mit einer Frist von {{ $contract->cancellation_days }} Tag(en) zum
        jeweiligen Monatsende zu kündigen. Das Recht zur außerordentlichen Kündigung aus wichtigem Grund bleibt hiervon unberührt.
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

<h3><strong>7.	Geheimhaltung und Datenschutz</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        Die Vertragsparteien verpflichten sich, alle ihnen während der Dauer dieser Vereinbarung bekannt gewordenen und
        anvertrauten Informationen, Erfahrungen und Kenntnisse aus dem Bereich des anderen Vertragspartners, soweit diese
        nach handelsüblicher Auffassung als Geschäftsgeheimnis anzusehen sind, während der Dauer dieser Rahmenvereinbarung
        sowie im Anschluss hieran für 2 Jahre streng vertraulich zu behandeln und weder für sich noch für oder durch Dritte
        zu verwerten oder verwerten zu lassen.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        Die Vertragsparteien verpflichten sich weiter, die einschlägigen Gesetze und Verordnungen zum Daten- und
        Geheimnisschutz einzuhalten. Dies umfasst insbesondere die Bestimmungen nach Art. 28 Abs. 3 lit. b, Art. 29 und Art.
        32 Abs. 4 DSGVO über die Verpflichtung auf das Datengeheimnis. Die Parteien werden die von ihnen zur Erbringung der
        vertragsgegenständlichen Leistungen eingesetzten Erfüllungsgehilfen im selben Umfang, wie sie selbst hierzu verpflichtet sind,
        auf den Daten- und Geheimnisschutz verpflichten und dies der jeweils anderen Partei auf Verlangen nachweisen.
    </span>
</p>

<h3><strong>8.	Aufsichtsrechtliche Anforderungen</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        Sollten aufgrund von Anordnungen, Rundschreiben, Merkblättern o. Ä. der Bundesanstalt für Finanzdienstleistungsaufsicht
        (BaFin) Anpassungen der Bestimmungen dieser Tippgebervereinbarung erforderlich werden, werden die Parteien diese
        Tippgebervereinbarung einvernehmlich abändern. Sollte aufgrund einer Anordnung der BaFin eine Beendigung dieses Vertrages
        erforderlich werden, stellt dies einen wichtigen Grund für die Kündigung dieses Vertrages dar.
    </span>
</p>

<h3><strong>9.	Compliance und Korruptionsbekämpfung</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a.	Die Parteien verpflichten sich, bei Anbahnung, Abschluss oder Durchführung dieses Vertrags, keinerlei Handlungen
        vorzunehmen, zu veranlassen oder zuzulassen, die dazu führen können, dass die Parteien oder die mit ihnen verbundenen
        Unternehmen die anwendbaren Gesetze oder Vorschriften verletzen, die der Bekämpfung der Korruption dienen. Diese Verpflichtung
        gilt insbesondere für das Angebot, das Versprechen oder die Gewährung von Vorteilen, einschließlich Beschleunigungszahlungen,
        an Amtsträger, für den öffentlichen Dienst besonders Verpflichtete, deren Angehörige oder diesen nahestehende Personen.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b.	Die Parteien verpflichten sich weiter, Angestellten oder Beauftragten der jeweils anderen Partei keinerlei Vorteile finanzieller
        oder anderer Art für diese, die andere Partei oder einen Dritten als Gegenleistung dafür anzubieten, zu versprechen oder zu gewähren,
        dass die eine Partei die andere Partei oder einen Dritten bei Anbahnung, Abschluss oder Durchführung dieses Vertrags in unlauterer
        Weise bevorzuge. Zugleich verpflichten sich die Parteien, dafür zu sorgen, dass Angestellte oder Beauftragte keinerlei Vorteile
        finanzieller oder anderer Art für sich, ihren Arbeit- oder Auftraggeber oder einen Dritten als Gegenleistung dafür fordern,
        sich versprechen lassen oder annehmen, dass sie einen anderen bei Anbahnung, Abschluss oder Durchführung dieses Vertrags
        in unlauterer Weise bevorzugen.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c.	Die Parteien haben sich jeweils unverzüglich zu benachrichtigen, sobald sie Kenntnis davon erlangen oder den begründeten
        Verdacht haben, dass bei Anbahnung, Abschluss oder Durchführung dieses Vertrags gegen die vorstehenden Bestimmungen verstoßen wurde.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        d.	Die Vertragsparteien sind jeweils berechtigt, diesen Vertrag außerordentlich zu kündigen, wenn sie Kenntnis davon erlangt
        oder den begründeten Verdacht hat, dass die jeweils andere Vertragspartei bei Anbahnung, Abschluss oder Durchführung dieses
        Vertrags gegen die vorstehenden Bestimmungen verstoßen hat.
    </span>
</p>

<p><br /></p>
<h3><strong>10. Schlussbestimmungen</strong></h3>
<p style="margin-left: 2rem;">
    <span>
        a.	Änderungen oder Ergänzungen dieses Vertrages, einschließlich dieser Schriftformklausel, bedürfen zu ihrer Wirksamkeit
        der Schriftform. Gleiches gilt für Neben- und Zusatzabreden.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        b.	Diese Vereinbarung einschließlich ihrer Anlagen und das Rechtsverhältnis zwischen Exporo
        und dem Partner unterliegen dem Recht der Bundesrepublik Deutschland.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        c.	Wenn der Partner Unternehmer ist, sind Erfüllungsort für alle Leistungen und Gerichtsstand für sämtliche
        Auseinandersetzungen aus und im Zusammenhang mit diesem Vertrag, soweit dies gesetzlich zulässig ist, Hamburg,
        Deutschland. Dies gilt auch dann, wenn der Partner keinen allgemeinen Gerichtsstand im Inland hat. Gesetzliche
        Regelungen über ausschließliche Zuständigkeiten bleiben unberührt.
    </span>
</p>
<p style="margin-left: 2rem;">
    <span>
        d.	Sollten sich einzelne Bestimmungen dieser Vereinbarung als ungültig oder undurchführbar erweisen, so wird
        dadurch die Gültigkeit der Vereinbarung im Übrigen nicht berührt. In einem solchen Fall sind die Parteien verpflichtet,
        die ungültige oder undurchführbare Bestimmung durch diejenige gesetzlich zulässige Bestimmung zu ersetzen, die den Zweck
        der ungültigen oder undurchführbaren Bestimmung, insbesondere das, was die Parteien gewollt haben, mit der weitestgehend
        möglichen Annäherung erreicht. Entsprechendes gilt, wenn sich bei Durchführung der Vereinbarung eine ergänzungsbedürftige
        Lücke ergeben sollte.
    </span>
</p>

<table style="min-width: 50vw; margin-top: 3rem">
    <tr>
        <td>
            <div style="border-bottom: 1px solid black">
                @if($contract->released_at !== null)
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
