@extends('bills.layout.bill')
@section('projects')

    <h1 class="text-center"> Übersicht Provisionsgutschrift</h1>

    <h2 class="text-center">Eigenumsatz</h2>
    <h3 class="text-center">Projekt</h3>
        <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
            <thead>
            <tr>
                <th>Anleger</th>
                <th class="text-center"> Projektname</th>
                <th class="text-center"> Projektlaufzeit in Monaten </th>
                <th class="text-center">Investitionsbetrag</th>
                <th class="text-center">Provision Netto</th>
                <th class="text-center">Provision Brutto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($investments as $investment)
                <tr>
                    <td> {{  $investment['lastName'] }}, {{ $investment['firstName'] }}</td>
                    <td class="text-center"> {{ $investment['projectName'] }}</td>
                    <td class="text-center"> {{ $investment['projectRuntime'] }}</td>
                    <td class="text-center"> {{ format_money($investment['investsum']) }}</td>
                    <td class="text-center">{{ format_money($investment['net']) }}</td>
                    <td class="text-center">{{ format_money($investment['gross']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
        <thead>
            <th class="text-center"> Total Investmentvolumen</th>
            <th class="text-center"> Total netto Provision</th>
            <th class="text-center"> Total brutto Provision</th>
        </thead>
        <tbody>
        <td class="text-center"> {{ format_money($investmentSum) }}</td>
        <td class="text-center"> {{ format_money($investmentNetSum) }}</td>
        <td class="text-center"> {{ format_money($investmentGrossSum) }}</td>
        </tbody>
    </table>
@endsection
