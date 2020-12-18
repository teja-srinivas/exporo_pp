<h4>Registrierungen</h4>

<div class="bg-white mb-5 shadow-sm print-break-avoid">
    <table class="table table-sm mb-0 table-foot-totals">
        <thead>
        <tr>
            <th class="border-top-0">Anleger</th>
            <th class="border-top-0 text-right">Registrierungsdatum</th>
            <th class="border-top-0 text-right">
                Provision
                @if ($user->productContract && $user->productContract->vat_included)
                    (netto)
                @endif
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($investors as $investor)
            <tr>
                <td>
                    <small class="mr-1">#{{ $investor['id'] }}</small>
                    {{ $investor['firstName'] }} {{ $investor['lastName'] }}

                    @unless(empty($investor['note']))
                    <br>
                    <div class="small">
                        <b>Notiz:</b>
                        <i>{{ $investor['note'] }}</i>
                    </div>
                    @endunless
                </td>
                <td class="text-right text-nowrap">{{ $investor['activationAt'] }}</td>
                <td class="text-right text-nowrap">{{ format_money((float) $investor['net']) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfooter>
            <tr>
                <td class="text-right font-weight-bold text-nowrap" colspan="2">Total Provision</td>
                <td class="text-right font-weight-bold text-nowrap">{{ format_money((float) $investorsNetSum) }}</td>
            </tr>
        </tfooter>
    </table>
</div>
