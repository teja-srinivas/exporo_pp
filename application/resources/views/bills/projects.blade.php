@section('projects')

    <h1 class="text-center mt-5"> Übersicht Provisionsgutschrift</h1>

    <h3 class="mt-5 text-center">Eigenumsatz</h3>

    @foreach($investments as $investment)
        <h5 class="mt-5">Projekt {{ $investment[0]['projectName'] }}</h5>
        <div class="d-flex">
            <div class="text-left flex-fill">Projektkürzel:</div>
            <div class="text-center flex-fill">Laufzeit: {{ $investment[0]['projectRuntime'] }}</div>
            <div class="text-right flex-fill">Marge: {{ $investment[0]['projectMargin'] }}%</div>
        </div>

        <table class="mt-2 table table-striped table-sm bg-white shadow-sm">
            <thead>
            <tr>
                <th>Anleger</th>
                <th class="text-right">Investitionsbetrag</th>
                <th class="text-right">Datum des Investments</th>
                <th class="text-right">Provision Netto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($investment as $investDetails)
                <tr>
                    <td>{{  $investDetails['lastName'] }}, {{ $investDetails['firstName'] }}</td>
                    <td class="text-right">{{ format_money($investDetails['investsum']) }}</td>
                    <td class="text-right">{{ $investDetails['investDate'] }}</td>
                    <td class="text-right">{{ format_money($investDetails['net']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach

    <table class="mt-5 table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
        <thead>
            <th class="text-right">Total Investmentvolumen</th>
            <th class="text-right">Total netto Provision</th>
        </thead>
        <tbody>
        <td class="text-right">{{ format_money($investmentSum) }}</td>
        <td class="text-right">{{ format_money($investmentNetSum) }}</td>
        </tbody>
    </table>
@endsection
