<h4>Umsatz Subpartner</h4>

<table class="table table-sm table-bordered border-left-0 border-right-0 mb-5 table-foot-totals">
    <thead>
        <tr class="bg-white">
            <th class="border-top-0">Partner</th>
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
    @foreach($overheads as $project)
        @php($details = $project[0])

        <tr class="bg-white">
            {{-- @if ($details['projectIsPrivatePlacement']) --}}
                <th scope="rowgroup">
                    <h5 class="m-0">{{ $details['projectName'] }}</h5>
                </th>
                <th scope="rowgroup" colspan="4" class="small text-muted align-middle">
                    Laufzeitfaktor: {{ $details['projectFactor'] }} ({{ $details['projectRuntime'] }} Monate)
                </th>
            {{--             @else
                            <th scope="rowgroup" colspan="5">
                                <h5 class="m-0">{{ $details['projectName'] }}</h5>
                            </th>
                        @endif
            --}}
        </tr>

        @foreach($project as $investment)
            <tr class="bg-white">
                <td rowspan="{{ empty($investment['note']) ? 1 : 2 }}">
                    {{ $investment['partnerName'] }}
                </td>
                <td class="text-right text-nowrap">{{ format_money((float) $investment['investsum']) }}</td>
                <td class="text-right text-nowrap">{{ $investment['investDate'] }}</td>
                @if ($details['projectIsPrivatePlacement'])
                    <td class="text-right text-nowrap">{{ $investment['bonus'] * $details['projectMargin'] }}% * {{ $details['projectFactor'] }}</td>
                @else
                    <td class="text-right text-nowrap">{{ $investment['bonus'] * $details['projectMargin'] }}%</td>
                @endif
                <td class="text-right text-nowrap">{{ format_money((float) $investment['net']) }}</td>
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
            <td class="text-right text-nowrap">
                Betrag Total
            </td>
            <td class="text-right text-nowrap border-left-0">
                {{ format_money((float) $project->sum('investsum')) }}
            </td>
            <td class="text-right text-nowrap" colspan="2">
                Provision Total
            </td>
            <td class="text-right text-nowrap border-left-0">
                {{ format_money((float) $project->sum('net')) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="py-3" style="border-width: 0 !important;"></td>
        </tr>
    @endforeach

    <tr class="bg-white">
        <td class="text-right font-weight-bold">Total Investmentvolumen</td>
        <td class="text-right font-weight-bold">{{ format_money((float) $overheadSum) }}</td>
        <td class="text-right font-weight-bold" colspan="2">Provision Total</td>
        <td class="text-right font-weight-bold">{{ format_money((float) $overheadNetSum) }}</td>
    </tr>
    </tbody>
</table>
