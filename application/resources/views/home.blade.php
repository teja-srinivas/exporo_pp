@extends('layouts.sidebar')

@section('title', 'Provisionsübersicht')

@section('main-content')
    <div class="card shadow-sm border-0 accent-primary mb-2">
        <div class="card-body py-3">
            <div class="row text-center">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Ausstehend
                    <div class="h2 mb-1">{{ format_money(max(0, $total - $approved - $paid)) }}</div>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Freigegeben
                    <div class="h2 mb-1">{{ format_money($approved) }}</div>
                </div>
                <div class="col-sm">
                    Ausgezahlt
                    <div class="h2 mb-1">{{ format_money($paid) }}</div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Auszahlungen</h4>

    <table class="bg-white shadow-sm accent-primary table table-borderless table-striped table-sm">
        <thead>
        <tr>
            <th>Monat</th>
            <th class="text-right">Summe</th>
            <th class="text-right">Provisionen</th>
            <th class="text-right" width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($bills as $bill)
            <tr>
                <td><a href="{{ route('bills.download', $bill) }}">{{ $bill->getDisplayName() }}</a></td>
                <td class="text-right">{{ format_money($bill->net) }}</td>
                <td class="text-right">{{ $bill->commissions }}</td>
                <td class="text-right">@timeago($bill->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Es wurden noch keine Abrechnungen ausgezahlt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
