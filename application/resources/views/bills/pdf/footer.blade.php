<table class="text-nowrap w-100" style="color: #aaa">
    <tr>
        <td class="align-top">
            <table>
                <tr>
                    <td colspan="2">
                        <strong>{{ $company->name }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        {{ $company->street }} {{ $company->street_no }},
                        {{ $company->postal_code }}, {{ $company->city }}
                    </td>
                </tr>
                <tr>
                    <td>Tel.:</td>
                    <td>{{ $company->phone_number }}</td>
                </tr>
                <tr>
                    <td>Fax.:</td>
                    <td>{{ $company->fax_number }}</td>
                </tr>
                <tr>
                    <td>E-Mail:&emsp;</td>
                    <td>{{ $company->email }}</td>
                </tr>
                <tr>
                    <td>Web:</td>
                    <td>{{ $company->website }}</td>
                </tr>
            </table>
        </td>
        <td style="width: 25px"></td>
        <td class="align-top">
            <table>
                <tr>
                    <td>Gesellschaftssitz:</td>
                    <td>Hamburg</td>
                </tr>
                <tr>
                    <td>Registergericht:</td>
                    <td>Amtsgericht Hamburg</td>
                </tr>
                <tr>
                    <td>Handelsregister:&emsp;</td>
                    <td>HRB 134393</td>
                </tr>
                <tr>
                    <td>Steuernummer:</td>
                    <td>48/719/02491</td>
                </tr>
                <tr>
                    <td>Aufsichtsrat:</td>
                    <td>Andreas Haug (Vorsitzender)</td>
                </tr>
                <tr>
                    <td class="align-top">Vorstand:</td>
                    <td class="align-top">
                        Simon Brunke (Vorsitzender)<br>
                        Dr. Björn Maronde<br>
                        Julian Oertzen
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 25px"></td>
        <td class="align-top">
            <table>
                <tr>
                    <td colspan="2">Bankverbindung</td>
                </tr>
                <tr>
                    <td>Bank:</td>
                    <td>Hamburger Sparkasse</td>
                </tr>
                <tr>
                    <td>IBAN:</td>
                    <td>DE37200505501238196024</td>
                </tr>
                <tr>
                    <td>BIC:</td>
                    <td>HASPDEHHXXX</td>
                </tr>
                <tr>
                    <td>Kto.-Nr.:&emsp;</td>
                    <td>1238196024</td>
                </tr>
                <tr>
                    <td>BLZ:</td>
                    <td>20050550</td>
                </tr>
            </table>
        </td>
        <td style="width: 25px"></td>
        <td class="align-top" style="width: 80px">
            <div class="text-uppercase mb-2">Ausgezeichnet</div>
            @if (file_exists(public_path('/images/ekomi_seal.png')))
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/ekomi_seal.png'))) }}"
                    alt="eKomi Kundenauszeichnung"
                    class="w-100"
                    style="opacity: 0.5"
                >
            @endif
        </td>
    </tr>
</table>
