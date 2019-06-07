<table class="small text-nowrap">
    <tr class="align-top">
        <td>
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
                    <td>E-Mail:</td>
                    <td>{{ $company->email }}</td>
                </tr>
                <tr>
                    <td>Web:</td>
                    <td>{{ $company->website }}</td>
                </tr>
            </table>
        </td>
        <td>
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
                    <td>Handelsregister:</td>
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
                <tr class="align-top">
                    <td>Vorstand:</td>
                    <td>
                        Simon Brunke (Vorsitzender)<br>
                        Dr. Björn Maronde<br>
                        Julian Oertzen
                    </td>
                </tr>
            </table>
        </td>
        <td>
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
                    <td>Kto.-Nr.:</td>
                    <td>1238196024</td>
                </tr>
                <tr>
                    <td>BLZ:</td>
                    <td>20050550</td>
                </tr>
            </table>
        </td>
        <td>
            <div class="text-uppercase mb-3">Ausgezeichnet</div>
            <img
                src="{{ asset('/images/ekomi_seal.png') }}"
                alt="eKomi Kundenauszeichnung"
                class="w-100"
            >
        </td>
    </tr>
    <tr>
        <td colspan="4 text-center py-3">
            Rechtsverbindliche Aussagen sind nur mit der Unterschrift
            eines Vorstandsmitgliedes gültig.
        </td>
    </tr>
</table>
