<!-- Page Header -->
<div class="row justify-content-between mb-5">
    <div class="col-5 pt-5 mt-5">
        <div class="mb-2 small pt-5">
            {{ $company->name }}&ensp;&bull;&ensp;{{ $company->street }}
            {{ $company->street_no }}&ensp;&bull;&ensp;{{ $company->postal_code }}
            {{ $company->city }}
        </div>
        <div class="lead">
            @php($fullName = trim($user->first_name . ' ' . $user->last_name))
            @php($userCompany = trim($user->details->company))

            @if(!empty($userCompany) && $userCompany !== $fullName)
                {{ $user->details->company }}<br>
            @endunless

            {{ $fullName }}<br>
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
    Hamburg, {{ now()->format('d.m.Y') }}
    <div class="lead font-weight-bold">Partner-ID: {{ $user->id }}</div>
    @if($bill)
        Rechnungsnummer: {{ $bill->id }}
    @endif

</div>

<!-- Page Content -->
<h3 class="mb-4">Provisionsgutschrift</h3>

<p>{{ $user->getGreeting() }},</p>
<p>
    sofern im Vormonat Provisionen angefallen sind, überweisen wir diese
    in den nächsten Tagen auf das von ihnen angegebene Konto:
</p>

<table class="table table-sm table-borderless w-50 mx-auto mb-4">
    <tbody>
    <tr>
        <th scope="row">IBAN</th>
        <td>{{ $user->details->iban }}</td>
    </tr>
    </tbody>
</table>

@php($sums = [
    'Umsatz' => $investmentNetSum,
    'Umsatz Subpartner' => $overheadNetSum,
    'Registrierungen' => $investorsNetSum,
    'Korrekturbuchungen' => $customNetSum,
])

<p>
    Die Provisionsgutschrift stellt sich wie folgt zusammen:
</p>

<div class="my-5">
    @php($total = array_sum($sums))
    @php($totalGross = $investmentGrossSum + $overheadGrossSum + $investorsGrossSum + $customGrossSum)

    @if($totalGross > 0)
    <table class="table table-sm mx-auto w-50 table-foot-totals">
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
        @if(abs($totalGross - $total) > 0)
            <tr>
                <th scope="row" class="text-right">zzgl. 19% MwSt.</th>
                <td class="text-right">{{ format_money($totalGross - $total) }}</td>
            </tr>
        @endif
            <tr>
                <th scope="row" class="text-right">Summe Gutschrift</th>
                <td class="font-weight-bold text-right">{{ format_money($totalGross) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
        <div class="text-center font-weight-bold">Für diesen Monat sind keine Provisionen angefallen.</div>
    @endif
</div>

<p class="text-justify mb-4">
    Die Abrechnung ist sofort nach Erhalt auf Richtigkeit hin zu überprüfen.
    Sie gilt als anerkannt, sofern nicht innerhalb von 4 Wochen nach dem Erhalt,
    dieser schriftlich gegenüber der
    <i>{{ $company->name }}, {{ $company->street }}
    {{ $company->street_no }},
    {{ $company->postal_code }} {{ $company->city }}</i>
    oder per Email an <a href="mailto:abrechnung@exporo.com">abrechnung@exporo.com</a>
    widersprochen wurde.
</p>

<p>
    Fragen zur Abrechnung richten Sie bitte schriftlich an
    <a href="mailto:abrechnung@exporo.com">abrechnung@exporo.com</a>.
</p>
