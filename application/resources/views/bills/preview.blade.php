@extends('bills.layout.bill')
@section('content')

    <h1> Übersicht Provisionsgutschrift</h1>
        <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
            <thead>
            <tr>
                <th>Anleger</th>
                <th class="text-right">Netto</th>
                <th class="text-right">Brutto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($commissions as $commission)
                <tr>
                    <td> {{  $commission['lastName'] }}, {{ $commission['firstName'] }}</td>
                    <td class="text-right">{{ format_money($commission['net']) }}</td>
                    <td class="text-right">{{ format_money($commission['gross']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
@endsection
