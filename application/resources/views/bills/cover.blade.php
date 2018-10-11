<h1 class="text-center">Deckblatt / Anschreiben Provisionsgutschrift</h1>
<div class="clearfix mt-5">
    <div class="d-flex flex-column">
        <div>
            <div>
                {{ $company->name }}, {{ $company->street }} {{ $company->street_no }}, {{ $company->postal_code }}
                , {{ $company->city }}
            </div>
        </div>
        <div class="p2">
            <div>
                {{ $user->first_name }} {{ $user->last_name}}
            </div>
            <div>
                {{ $user->details->address_street }} {{ $user->details->address_number }}
            </div>
            <div>
                {{ $user->details->address_zipcode }}, {{ $user->details->address_city }}
            </div>
        </div>

    </div>
    <div class="d-flex flex-column float-right clearfix">
        <h4> {{ $company->name }}</h4>
        <div class="p2 mt-1">
            Telefon: {{  $company->phone_number }}
        </div>
        <div class="p2 mt-1">
            Fax: {{  $company->fax_number }}
        </div>
        <div class="p2 mt-1">
            E-Mail: {{  $company->email }}
        </div>
        <div class="p2 mt-1">
            Webseite:
            <a href="http://{{  $company->website }}" target="_blank">
                {{  $company->website }}
            </a>
        </div>
        <div class="p2 mt-4">
            Hamburg, {{  \Carbon\Carbon::NOW()->format('d.m.Y') }}
        </div>
        <div class="p2 mt-1">
            Partner-ID {{ $user->id }}
        </div>
    </div>

    <div class="d-flex flex-column">

    </div>
</div>

<h3 class="text-center mt-5">Provisionsgutschrift</h3>
<div class="mt-5">
    Sofern Provisionen angefallen sind, überweisen wir diese in den nächsten Tagen auf das von Ihnen angegebene
    Konto {{ $user->details->iban  }}
</div>
<div>
    Die Provisionsgutschrift stellt sich wie folgt zusammen:
</div>
<div class="mt-3">
    <table class="table-bordered">
        <tr>
            <th scope="row">Eigenumsatz</th>
            <td>{{ format_money($investmentNetSum) }}</td>
        </tr>
        <tr>
            <th>Teamumsatz</th>
            <td></td>
        </tr>
        <tr>
            <th>Registrierungen</th>
            <td></td>
        </tr>
        <tr>
            <th>Korrekturbuchungen</th>
            <td></td>
        </tr>
        <tr>
            <th>Mehrwertsteuer</th>
            <td></td>
        </tr>
        <tr>
            <th>Summe Gutschrift</th>
            <td></td>
        </tr>
    </table>
</div>
<div class="mt-3 print-break">
    <div>
        Die Abrechnung ist sofort nach Erhalt auf Richtigkeit hin zu überprüfen. Sie gilt als anerkannt, sofern
        nicht innerhalb von 4 Wochen nach
        dem Erhalt, dieser schriftlich gegenüber der
        {{ $company->name }}, {{ $company->street }}
        {{ $company->street_no }},
        {{ $company->postal_code }} {{ $company->city }}
        oder per Email an abrechnung@exporo.com
        widersprochen wurde.
    </div>
    <div class="mt-2">
        Fragen zur Abrechnung richten Sie bitte schriftlich an abrechnung@exporo.com.
    </div>
</div>
