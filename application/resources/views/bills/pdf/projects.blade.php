<h4>Umsatz</h4>

<table class="table table-sm table-bordered border-left-0 border-right-0 mb-5 table-foot-totals">
    <thead>
        <tr class="bg-white">
            <th class="border-top-0">Anleger</th>
            <th class="border-top-0">Betrag</th>
            <th class="border-top-0">Datum</th>
            <th class="border-top-0">Formel</th>
            <th class="border-top-0">Provision</th>
        </tr>
        <tr>
            <td colspan="5" class="py-2" style="border-width: 0 !important;"></td>
        </tr>
    </thead>

    <tbody>
    @foreach($investments as $project)
        @php($details = $project[0])

        <tr class="bg-white">
            <th scope="rowgroup">
                <h5 class="m-0">{{ $details['projectName'] }}</h5>
            </th>
            <th scope="rowgroup" colspan="4" class="small text-muted align-middle">
                Laufzeitfaktor: {{ $details['projectFactor'] }} ({{ $details['projectRuntime'] }} Monate)
            </th>
        </tr>

    @foreach($project as $investment)
        <tr class="bg-white">
            <td rowspan="{{ empty($investment['note']) ? 1 : 2 }}">
                <small class="mr-1">#{{ $investment['id'] }}</small>
                {{ $investment['firstName'] }} {{ $investment['lastName'] }}
            </td>
            <td class="text-right">{{ format_money($investment['investsum']) }}</td>
            <td class="text-right">{{ $investment['investDate'] }}</td>
            <td class="text-right">{{ $investment['bonus'] * $details['projectMargin'] }}% * {{ $details['projectFactor'] }}</td>
            <td class="text-right">{{ format_money($investment['net']) }}</td>
        </tr>
        @unless(empty($investment['note']))
        <tr class="bg-white">
            <td colspan="4" class="small">
                <b>Notiz:</b>
                <i>{{ $investment['note'] }}</i>
            </td>
        </tr>
        @endunless
    @endforeach

        <tr class="font-weight-bold shadow-sm bg-white">
            <td class="text-right">
                Betrag Total
            </td>
            <td class="text-right border-left-0">
                {{ format_money($project->sum('investsum')) }}
            </td>
            <td class="text-right" colspan="2">
                Provision Total
            </td>
            <td class="text-right border-left-0">
                {{ format_money($project->sum('net')) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="border-0 py-3" style="border-width: 0 !important;"></td>
        </tr>
    @endforeach

        <tr class="bg-white">
            <td class="text-right font-weight-bold">Total Investmentvolumen</td>
            <td class="text-right font-weight-bold">{{ format_money($investmentSum) }}</td>
            <td class="text-right font-weight-bold" colspan="2">Provision Total</td>
            <td class="text-right font-weight-bold">{{ format_money($investmentNetSum) }}</td>
        </tr>
    </tbody>
</table>
