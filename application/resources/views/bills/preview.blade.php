@extends('bills.layout.bill')
@section('projects')

    <h1 class="text-center"> Übersicht Provisionsgutschrift</h1>

    <h2 class="text-center">Eigenumsatz</h2>
    @foreach($investments as $investment)
    <h3 class="mt-5 text-center">Projekt {{ $investment[0]['projectName'] }}</h3>
    <div class="kpi_headline">
        <div class="text-left"> Projektkürzel:</div>
        <div class="text-center">Projektlaufzeit: {{ $investment[0]['projectRuntime'] }}</div>
        <div class="text-right">Projektmarge: {{ $investment[0]['projectMargin'] }}%</div>
    </div>
        <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary">
            <thead>
            <tr>
                <th>Anleger</th>
                <th class="text-center"> Investitionsbetrag </th>
                <th class="text-center">Datum des Investments</th>
                <th class="text-center">Provision Netto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($investment as $investDetails)
                <tr>
                    <td> {{  $investDetails['lastName'] }}, {{ $investDetails['firstName'] }}</td>
                    <td class="text-center"> {{ format_money($investDetails['investsum']) }}</td>
                    <td class="text-center"> {{ $investDetails['investDate'] }}</td>
                    <td class="text-center">{{ format_money($investDetails['net']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
        <thead>
            <th class="text-center"> Total Investmentvolumen</th>
            <th class="text-center"> Total netto Provision</th>
        </thead>
        <tbody>
        <td class="text-center"> {{ format_money($investmentSum) }}</td>
        <td class="text-center"> {{ format_money($investmentNetSum) }}</td>
        </tbody>
    </table>
@endsection
