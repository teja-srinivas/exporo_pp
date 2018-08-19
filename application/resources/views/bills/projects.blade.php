@foreach($investments as $project)
    <div class="bg-white my-3 p-2 shadow-sm border-0 print-break-avoid">
        <div class="my-2">
            <h5>
                Projekt {{ $project[0]['projectName'] }}

                <small class="text-muted text-right ml-3">
                    Projektkürzel:
                    &nbsp;&ndash;&nbsp;
                    Laufzeit: {{ $project[0]['projectRuntime'] }} Monate
                    &nbsp;&ndash;&nbsp;
                    Marge: {{ $project[0]['projectMargin'] }}%
                </small>
            </h5>
        </div>

        <table class="table table-striped table-sm mb-0 table-foot-totals">
            <thead>
            <tr>
                <th class="border-top-0">Anleger</th>
                <th class="border-top-0 text-right">Investitionsbetrag</th>
                <th class="border-top-0 text-right">Datum des Investments</th>
                <th class="border-top-0 text-right">Provision Netto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($project->sortNatural('lastName') as $investment)
                <tr>
                    <td>{{  $investment['lastName'] }}, {{ $investment['firstName'] }}</td>
                    <td class="text-right">{{ format_money($investment['investsum']) }}</td>
                    <td class="text-right">{{ $investment['investDate'] }}</td>
                    <td class="text-right">{{ format_money($investment['net']) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="font-weight-bold">
                <td class="text-right">
                    Investitionsbetrag Total
                </td>
                <td class="text-right">
                    {{ format_money($project->sum('investsum')) }}
                </td>
                <td class="text-right">
                    Provision Netto Total
                </td>
                <td class="text-right">
                    {{ format_money($project->sum('net')) }}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
@endforeach

<table class="mt-3 table table-borderless lead table-sm">
    <tbody>
    <tr>
        <td class="text-right font-weight-bold">Total Investmentvolumen</td>
        <td class="text-right" width="150">{{ format_money($investmentSum) }}</td>
    </tr>
    <tr>
        <td class="text-right font-weight-bold">Total Netto Provision</td>
        <td class="text-right" width="150">{{ format_money($investmentNetSum) }}</td>
    </tr>
    </tbody>
</table>
