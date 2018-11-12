<h3>Eigenumsatz</h3>

<table class="table table-sm table-bordered border-left-0 border-right-0 my-3 table-foot-totals">
    <thead>
        <tr>
            <th class="border-top-0">Anleger</th>
            <th class="border-top-0">Investitionsbetrag</th>
            <th class="border-top-0">Investmentdatum</th>
            <th class="border-top-0">Formel</th>
            <th class="border-top-0">Provision Netto</th>
        </tr>
    </thead>

    <tbody>
    @foreach($investments as $project)
        @php($factor = $project[0]['projectFactor'] * ($project[0]['projectMargin'] / 100))

        <tr>
            <th scope="rowgroup">
                <h5 class="m-0">{{ $project[0]['projectName'] }}</h5>
            </th>
            <th scope="rowgroup" colspan="4" class="small text-muted align-middle">
                Laufzeit: {{ $project[0]['projectRuntime'] }} Monate
                &nbsp;&ndash;&nbsp;
                Marge: {{ $project[0]['projectMargin'] }}%
                &nbsp;&ndash;&nbsp;
                Faktor: {{ $factor }}
                ({{ $project[0]['projectFactor'] }} * {{ $project[0]['projectMargin'] }})
            </th>
        </tr>

    @foreach($project->sortNatural('lastName') as $investment)
        <tr>
            <td>{{  $investment['lastName'] }}, {{ $investment['firstName'] }}</td>
            <td class="text-right">{{ format_money($investment['investsum']) }}</td>
            <td class="text-right">{{ $investment['investDate'] }}</td>
            <td class="text-right">{{ $investment['bonus'] }}% * {{ $factor }}</td>
            <td class="text-right">{{ format_money($investment['net']) }}</td>
        </tr>
    @endforeach

        <tr class="font-weight-bold shadow-sm">
            <td class="text-right">
                Investitionsbetrag Total
            </td>
            <td class="text-right border-left-0">
                {{ format_money($project->sum('investsum')) }}
            </td>
            <td class="text-right" colspan="2">
                Provision Netto Total
            </td>
            <td class="text-right border-left-0">
                {{ format_money($project->sum('net')) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="border-0 py-3" style="border-width: 0 !important;"></td>
        </tr>
    @endforeach

        <tr>
            <td class="text-right font-weight-bold" colspan="4">Total Investmentvolumen</td>
            <td class="text-right">{{ format_money($investmentSum) }}</td>
        </tr>
        <tr>
            <td class="text-right font-weight-bold" colspan="4">Netto Provision</td>
            <td class="text-right">{{ format_money($investmentNetSum) }}</td>
        </tr>
    </tbody>
</table>
