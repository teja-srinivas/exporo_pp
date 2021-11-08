<h1 style="text-align: center"><strong>Nachtrag zur Tippgebervereinbarung</strong></h1>
<p style="text-align: center;">zwischen</p>
<p>
    der EPH Investment GmbH,<br>
    Am Sandtorkai 70, 20457 Hamburg, HRB Hamburg 146341,
    vertreten durch ihre Geschäftsführer Patrick Hartmann, Herman Tange.
</p>
<p>
    der Exporo AG,<br>
    Am Sandtorkai 70, 20457 Hamburg, HRB Hamburg 134393,
    Vorstand: Simon Brunke (Vorsitzender) Dr. Björn Maronde, Herman Tange
</p>
<p style="text-align: right;">- im Folgenden „Exporo“ genannt -</p>
<p style="text-align: center;">und</p>
<p>
    @unless(empty($user->details->company))
        der Firma {{ $user->details->company }}<br>
        vertr. durch
    @endunless
    {{ $user->first_name }}
    {{ $user->last_name }},<br>
    {{ $user->details->address_street }}
    {{ $user->details->address_number }},
    {{ $user->details->address_zipcode }}
    {{ $user->details->address_city }}
</p>
<p style="text-align: right;">- im Folgenden „Partner“ genannt –</p>

<h3><strong>1. Eintritt der Exporo AG in die Tippgebervereinbarung</strong></h3>
<p style="margin-left: 2rem;">
    Die Parteien sind sich darüber einig, dass die Exporo AG als weiterer Vertragspartner neben der EPH Investment GmbH in die Tippgebervereinbarung mit dem Partner eintritt.
</p>

<h3><strong>2. Zahlung der Vergütungen</strong></h3>
<p style="margin-left: 2rem;">
    Sämtliche Vergütungen, die Exporo gegenüber dem Partner zu erbringen hat, werden ausschließlich von der Exporo AG geleistet.
</p>

<h3><strong>3. Namhaftmachung der PROPVEST Produkte</strong></h3>
<p style="margin-left: 2rem;">
    Soweit der Partner auch die auf der PROPVEST Plattform von Exporo vertriebenen Produkte „PROPVEST Select“ und „PROPVEST Immobilien-Sparplan“ gegenüber Kunden namhaft macht, ist beabsichtigt, zu einem späteren Zeitpunkt auch eine Bestandsprovision (laufende Provision) in dem Anhang „Provisionsvereinbarungen“ der Tippgebervereinbarung aufzunehmen. Es sind hierzu aber noch technische und organisatorische Maßnahmen umzusetzen. Exporo wird den Partner informieren und die Provisionsbestimmungen entsprechend ergänzen, sobald eine Umsetzung erfolgen kann.
</p>

<h3><strong>4. Umsatzsteuer „PROPVEST Immobilien-Sparplan“</strong></h3>
<p style="margin-left: 2rem;">
    Soweit zu einem späteren Zeitpunkt Provision für den „PROPVEST Immobilien-Sparplan“ geleistet wird, fällt hierfür, unabhängig davon, ob es sich dabei um Abschluss- oder Bestandsprovision handelt, Umsatzsteuer an (gelten die Provisionssätze somit jeweils zuzüglich Ust.), da es sich bei dem Sparplan um eine digitale Vermögensverwaltung handelt und diese Tätigkeit nach derzeitiger Rechtslage nicht von der Umsatzsteuer befreit ist.
</p>
<p style="margin-left: 2rem;">
    Es gelten im Übrigen weiterhin sämtliche Regelungen aus der Tippgebervereinbarung, soweit in diesem Nachtrag nichts Abweichendes geregelt wurde.
</p>

<table style="min-width: 50vw; margin-top: 3rem" cellspacing="15">
    <tr>
        <td style="width:50%;">
            <div style="border-bottom: 1px solid black">
                Hamburg, {{ date('d.m.Y')  }}
            </div>
            <p style="margin-top: 0.25rem">Ort, Datum</p>
        </td>
        <td style="width:50%; padding-top: 3rem">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="padding-top: 3rem"></td>
    </tr>
    <tr>
        <td style="width:50%; vertical-align: bottom">
            <div style="border-bottom: 1px solid black">
                <img
                    src="{{ url('/images/unterschrift_ag.png') }}"
                    alt="Unterschrift Exporo Simon Brunke & Patrick Hartmann"
                    style="width: 90%"
                >
            </div>
            <p style="margin-top: 0.25rem">Exporo AG</p>
        </td>
        <td style="width:50%;">&nbsp;</td>
    </tr>
    <tr>
        <td style="width:50%; vertical-align: bottom">
            <div style="border-bottom: 1px solid black">
                <img
                    src="{{ url('/images/unterschrift_eph.png') }}"
                    alt="Unterschrift EPH Patrick Hartmann"
                    style="width: 50%"
                >
            </div>
            <p style="margin-top: 0.25rem">EPH Investment GmbH</p>
        </td>
        <td style="width: 50%; vertical-align: bottom">
            <div style="border-bottom: 1px solid black">
                &nbsp;
            </div>
            <p style="margin-top: 0.25rem">Partner</p>
        </td>
    </tr>
</table>
