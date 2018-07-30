@extends('layouts.sidebar')

@section('title')
    Rechnungen Erstellen
@endsection

@section('actions')
    <form action="{{ route('bills.store') }}" method="POST">
        @csrf

        <div class="d-flex align-items-center">
            <strong>Veröffentlichen:</strong>
            <input type="date" name="release_at" required class="form-control mx-2">
            <button class="btn btn-primary">Erstellen</button>
        </div>
    </form>
@endsection

@section('main-content')
    <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
        <thead>
        <tr>
            <th>Partner</th>
            <th class="text-right">Summe</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bills as $bill)
            <tr>
                <td>{{ $bill['lastName'] }}, {{ $bill['firstName'] }}</td>
                <td class="text-right">{{ format_money($bill['sum']) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" class="font-weight-bold text-right lead">
                {{ format_money($bills->sum('sum')) }}
            </td>
        </tr>
        </tfoot>
    </table>
@endsection
