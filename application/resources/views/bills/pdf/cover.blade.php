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
<h4 class="mb-4">Provisionsgutschrift {{ __('time.monthname.' . $bill->released_at->subMonth(1)->formatLocalized('%m')) }} {{ $bill->released_at->subMonth(1)->formatLocalized('%Y') }}</h4>
<h5 class="mb-5">Gemäß Partnervertrag mit der Exporo AG und der EPH Investment GmbH vom {{ $user->partnerContract->accepted_at->format('d.m.Y') }}</h5>

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
                    <th scope="row">
                    {{ $title }}
                    @if ($user->productContract && $user->productContract->vat_included)
                            (Netto)
                    @endif
                    </td>
                    <td class="text-right">{{ format_money((float) $sum) }}</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <th scope="row">
                    Umsatz Neukunden
                    @if ($user->productContract && $user->productContract->vat_included)
                        (Netto)
                    @endif
                </th>
                <td class="text-right">{{ format_money((float) ($investmentsSumFirstInvestment + $overheadsSumFirstInvestment)) }}</td>
            </tr>
            <tr>
                <th scope="row">
                    Umsatz Bestandskunden
                    @if ($user->productContract && $user->productContract->vat_included)
                        (Netto)
                    @endif
                </th>
                <td class="text-right">{{ format_money((float) ($investmentsSumNoneFirstInvestment + $overheadsSumNoneFirstInvestment)) }}</td>
            </tr>

            @if ($user->productContract && $user->productContract->vat_amount > 0)
                @php($vatPercent = $user->productContract->vat_amount)

                @if ($user->productContract->vat_included)
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th scope="row">Umsatz (Brutto)</th>
                        <td class="text-right">
                            {{ format_money(max(0, $totalGross)) }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-right">inkl. {{ $vatPercent }}% MwSt.</th>
                        <td class="text-right">
                            {{ format_money((float) (($totalGross / (100 + $vatPercent)) * $vatPercent)) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-right">Summe Gutschrift</th>
                        <td class="font-weight-bold text-right">{{ format_money(max(0, $totalGross)) }}</td>
                    </tr>
                @else
                    <tr>
                        <th scope="row" class="text-right">zzgl. {{ $vatPercent }}% MwSt.</th>
                        <td class="text-right">{{ format_money((float) ($totalGross - $total)) }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-right">Summe Gutschrift</th>
                        <td class="font-weight-bold text-right">{{ format_money(max(0, $totalGross)) }}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <th scope="row" class="text-right">Summe Gutschrift</th>
                    <td class="font-weight-bold text-right">{{ format_money(max(0, $totalGross)) }}</td>
                </tr>
            @endif

            </tfoot>
        </table>
    @else
        <div class="text-center font-weight-bold">Für diesen Monat sind keine Provisionen angefallen.</div>
    @endif
</div>

<p class="text-justify mb-4">
    @if(bccomp(abs($totalGross - $total), 0, 2) === 0)
        Die vergütete Provision ist nach unserer Einschätzung gem. § 4 Nr. 8a UStG ein
        steuerfreier Umsatz. Für den Fall, dass die seitens der {{ $company->name }}
        gezahlten Provisionen im Nachgang von einer Steuerbehörde als umsatzsteuerpflichtig
        bewertet werden sollten, gilt die oben abgerechnete Provision als Bruttobetrag inkl.
        der zu zahlenden Umsatzsteuer und ist diese sodann entsprechend nachzuzahlen.
    @endif
</p>

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
