<!-- Page Header -->
<div class="row justify-content-between">
    <div class="col-6 pt-5 mt-5">
        <div class="mb-2 small pt-5">
            {{ $company->name }}&ensp;&bull;&ensp;{{ $company->street }}
            {{ $company->street_no }}&ensp;&bull;&ensp;{{ $company->postal_code }}
            {{ $company->city }}
        </div>
        <div>
            @php($fullName = trim($user->first_name . ' ' . $user->last_name))
            @php($userCompany = trim($user->details->company))
            @php($isCompany = !empty($userCompany) && ($userCompany !== $fullName))

            @if($isCompany)
                {{ $user->details->company }}<br>
            @else
                {{ $fullName }}<br>
            @endunless

            {{ $user->details->address_street }} {{ $user->details->address_number }}<br>
            {{ $user->details->address_zipcode }} {{ $user->details->address_city }}<br>

            @if($user->details->isFromCountry('at'))
                <div class="mt-2">USt-IdNr.: {{ $user->details->vat_id }}</div>
            @endif
        </div>
    </div>
    <div class="col-6 text-right small">
        <h3>{{ $company->name }}</h3>

        @unless(empty($company->phone_number))
        <div class="my-3">
            <strong>Telefon</strong><br>
            {{ $company->parsePhoneNumber($company->phone_number) }}
        </div>
        @endunless

        @unless(empty($company->fax_number))
        <div class="my-3">
            <strong>Fax</strong><br>
            {{ $company->parsePhoneNumber($company->fax_number) }}
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

<div class="text-right">
    Hamburg, {{ optional($bill->released_at)->format('d.m.Y') }}
    <div class="lead font-weight-bold">Partner-ID: {{ $user->id }}</div>

    @if($bill->exists)
        Rechnungsnummer: {{ $bill->getKey() }}
    @endif
</div>

<!-- Page Content -->
<h4 class="mb-4">Provisionsgutschrift</h4>

<p>
    @if($isCompany)
        Sehr geehrte Damen und Herren,
    @else
        {{ $user->getGreeting() }},
    @endif
</p>
<p>
    sofern im Vormonat Provisionen angefallen sind, überweisen wir diese
    in den nächsten Tagen auf das von Ihnen angegebene Konto:
</p>

<table class="table table-sm table-borderless w-50 mx-auto mb-3">
    <tbody>
    <tr>
        <th scope="row">IBAN</th>
        <td>{{ $user->details->getFormattedIban() }}</td>
    </tr>
    </tbody>
</table>

@php($sums = [
    'Umsatz' => $investmentNetSum,
    'Umsatz Subpartner' => $overheadNetSum,
    'Registrierungen' => $investorsNetSum,
    'Sonderbuchung' => $customNetSum,
])

<p>
    Der Leistungszeitraum entspricht dem Investment- oder Registrierungsdatum.
    Die Provisionsgutschrift stellt sich wie folgt zusammen:
</p>

<div class="my-3">
    @php($total = array_sum($sums))
    @php($totalGross = $investmentGrossSum + $overheadGrossSum + $investorsGrossSum + $customGrossSum)

    @if(count(array_filter($sums)) > 0)
    <table class="table table-sm mx-auto w-50 table-foot-totals">
        <tbody>
        @foreach($sums as $title => $sum)
            @continue($sum === 0)
            <tr>
                <th scope="row">{{ $title }}</td>
                <td class="text-right">{{ format_money((float) $sum) }}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        @if(abs($totalGross - $total) > 0)
            <tr>
                <th scope="row" class="text-right">zzgl. 16% MwSt.</th>
                <td class="text-right">{{ format_money((float) ($totalGross - $total)) }}</td>
            </tr>
        @endif
            @if (($totalGross === $total) &&
                $user->details->vat_included &&
                $user->productContract &&
                $user->productContract->vat_included &&
                $user->productContract->vat_amount > 0
                )
                <tr>
                    <th scope="row" class="text-right">inkl. {{ $user->productContract->vat_amount }}% MwSt.</th>
                    <td class="text-right">{{ format_money((float) ($totalGross * ($user->productContract->vat_amount / 100))) }}</td>
                </tr>
            @endif
            <tr>
                <th scope="row" class="text-right">Summe Gutschrift</th>
                <td class="font-weight-bold text-right">{{ format_money(max(0, $totalGross)) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
        <div class="text-center font-weight-bold">Für diesen Monat sind keine Provisionen angefallen.</div>
    @endif
</div>

@if(bccomp(abs($totalGross - $total), 0, 2) === 0)
<p class="text-justify mb-4">
    Die vergütete Provision ist gem. § 4 Nr. 8a UStG ein steuerfreier Umsatz.
    Für den Fall, dass die seitens der {{ $company->name }} gezahlten Provisionen als
    umsatzsteuerpflichtig bewertet werden sollten, so gilt die oben abgerechnete
    Provision als Bruttobetrag inkl. der zu zahlenden Umsatzsteuer.
</p>
@endif

<p class="text-justify mb-4">
    Die Abrechnung ist sofort nach Erhalt auf Richtigkeit hin zu überprüfen.
    Sie gilt als anerkannt, sofern nicht innerhalb von 4 Wochen nach dem Erhalt,
    dieser schriftlich gegenüber der
    <i>{{ $company->name }}, {{ $company->street }}
    {{ $company->street_no }},
    {{ $company->postal_code }} {{ $company->city }}</i>
    oder per Email an <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
    widersprochen wurde.
</p>

<p>
    Fragen zur Abrechnung richten Sie bitte schriftlich an
    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>.
</p>
