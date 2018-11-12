<!-- Page Header -->
<div class="row justify-content-between mb-5">
    <div class="col-5 pt-5 mt-5">
        <div class="mb-2 small pt-5">
            {{ $company->name }}&ensp;&bull;&ensp;{{ $company->street }}
            {{ $company->street_no }}&ensp;&bull;&ensp;{{ $company->postal_code }}
            {{ $company->city }}
        </div>
        <div class="lead">
            @unless(empty($user->details->company))
                {{ $user->details->company }}<br>
            @endunless

            {{ $user->first_name }} {{ $user->last_name}}<br>
            {{ $user->details->address_street }} {{ $user->details->address_number }}<br>
            {{ $user->details->address_zipcode }} {{ $user->details->address_city }}<br>
        </div>
    </div>
    <div class="col-5 text-right small">
        <h3>{{ $company->name }}</h3>

        @unless(empty($company->phone_number))
        <div class="my-3">
            <strong>Telefon</strong><br>
            {{ $company->phone_number }}
        </div>
        @endunless

        @unless(empty($company->fax_number))
        <div class="my-3">
            <strong>Fax</strong><br>
            {{ $company->fax_number }}
        </div>
        @endunless

        @unless(empty($company->email))
        <div class="my-3">
            <strong>E-Mail</strong><br>
            <a href="mailto:{{ $company->email }}" target="_blank">
                {{ $company->email }}
            </a>
        </div>
        @endunless

        @unless(empty($company->website))
        <div class="my-3">
            <strong>Webseite</strong><br>
            <a href="https://{{ $company->website }}" target="_blank">
                {{ $company->website }}
            </a>
        </div>
        @endunless
    </div>
</div>

<div class="text-right my-4">
    <div class="lead font-weight-bold">Partner-ID: {{ $user->id }}</div>
    Hamburg, {{ now()->format('d.m.Y') }}
</div>

<!-- Page Content -->
<h3 class="mb-4">Provisionsgutschrift</h3>

<p>
    Sofern Provisionen angefallen sind, überweisen wir diese in den nächsten Tagen auf das von Ihnen angegebene
    Konto {{ $user->details->iban }}
</p>
<p>
    Die Provisionsgutschrift stellt sich wie folgt zusammen:
</p>

<div class="my-5">
    <table class="table table-sm mx-auto w-50 table-foot-totals">
        @php($sums = [
            'Eigenumsatz' => $investmentNetSum,
            'Umsatz Subpartner' => 0,
            'Registrierungen' => 0,
            'Korrekturbuchungen' => 0,
        ])

        @php($total = array_sum($sums))

        <tbody>
        @foreach($sums as $title => $sum)
            @continue($sum <= 0)
            <tr>
                <th scope="row">{{ $title }}</td>
                <td class="text-right">{{ format_money($sum) }}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        @if($user->details->vat_included !== null)
            <tr>
                <th scope="row" class="text-right">19% MwSt.</th>
                <td class="text-right">{{ format_money(abs($totalCommission)) }}</td>
            </tr>
        @endif
            <tr>
                <th scope="row" class="text-right">Summe Gutschrift</th>
                <td class="font-weight-bold text-right">{{ format_money($total) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<p>
    Die Abrechnung ist sofort nach Erhalt auf Richtigkeit hin zu überprüfen.
    Sie gilt als anerkannt, sofern nicht innerhalb von 4 Wochen nach dem Erhalt,
    dieser schriftlich gegenüber der
    <i>{{ $company->name }}, {{ $company->street }}
    {{ $company->street_no }},
    {{ $company->postal_code }} {{ $company->city }}</i>
    oder per Email an <a href="mailto:abrechnung@exporo.com">abrechnung@exporo.com</a>
    widersprochen wurde.
</p>

<p class="mb-5">
    Fragen zur Abrechnung richten Sie bitte schriftlich an
    <a href="mailto:abrechnung@exporo.com">abrechnung@exporo.com</a>.
</p>
