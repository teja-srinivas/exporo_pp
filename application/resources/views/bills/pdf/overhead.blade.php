<h3>Teamumsatz</h3>

<table class="table table-sm table-bordered border-left-0 border-right-0 my-3 table-foot-totals">
    <thead>
    <tr>
        <th class="border-top-0">Sub-ID</th>
        <th class="border-top-0">Anleger</th>
        <th class="border-top-0">Investitionsbetrag</th>
        <th class="border-top-0">Investmentdatum</th>
        <th class="border-top-0">Provision Netto</th>
        <th class="border-top-0">Projektname</th>
        <th class="border-top-0">ProjektID</th>
    </tr>
    </thead>

    <tbody>

    @foreach($overheads as $overhead)
        <tr>
            <td>{{$overhead['partnerId']}}</td>
            <td>{{  $overhead['lastName'] }}, {{ $overhead['firstName'] }}</td>
            <td class="text-right">{{ format_money($overhead['investsum']) }}</td>
            <td class="text-right">{{ $overhead['investDate'] }}</td>
            <td class="text-right">{{ format_money($overhead['net']) }}</td>
            <td class="text-right">{{ $overhead['projectName'] }}</td>
            <td class="text-right">{{ $overhead['projectId'] }}</td>
        </tr>

    @endforeach

    <tr>
        <td class="text-right font-weight-bold" colspan="4">Total Teaminvests</td>
        <td class="text-right">{{ format_money($overheadSum) }}</td>
    </tr>
    <tr>
        <td class="text-right font-weight-bold" colspan="4">Netto Provision</td>
        <td class="text-right">{{ format_money($overheadNetSum) }}</td>
    </tr>
    </tbody>
</table>
