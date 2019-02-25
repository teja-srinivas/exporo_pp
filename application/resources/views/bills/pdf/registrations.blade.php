<h4>Registrierungen</h4>

<div class="bg-white mb-5 shadow-sm print-break-avoid">
    <table class="table table-sm mb-0 table-foot-totals">
        <thead>
        <tr>
            <th class="border-top-0">Anleger</th>
            <th class="border-top-0 text-right">Registrierungsdatum</th>
            <th class="border-top-0 text-right">Provision</th>
        </tr>
        </thead>
        <tbody>
        @foreach($investors as $investor)
            <tr>
                <td>
                    {{ $investor['firstName'] }} {{ $investor['lastName'] }}
                    @unless(empty($investor['note']))
                    <br>
                    <div class="small">
                        <b>Notiz:</b>
                        <i>{{ $investor['note'] }}</i>
                    </div>
                    @endunless
                </td>
                <td class="text-right">{{ $investor['activationAt'] }}</td>
                <td class="text-right">{{ format_money($investor['net']) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfooter>
            <tr>
                <td class="text-right font-weight-bold" colspan="2">Total Provision</td>
                <td class="text-right font-weight-bold">{{ format_money($investorsNetSum) }}</td>
            </tr>
        </tfooter>
    </table>
</div>
