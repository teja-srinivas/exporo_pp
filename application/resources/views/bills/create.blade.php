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
    @if($bills->isEmpty())
        @card
        <div class="lead text-center text-muted">
            Es gibt derzeit keine Provisionen, aus denen Rechnungen erstellt werden können.
        </div>
        @endcard
    @else
    <table class="table table-borderless table-striped table-sm bg-white shadow-sm accent-primary table-sticky">
        <thead>
        <tr>
            <th>Partner</th>
            <th class="text-right">Summe</th>
            <th class="text-right">Vorschau</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bills as $bill)
            <tr>
                <td>
                    <a href="{{ route('users.show', $bill['userId']) }}">
                        <span class="text-muted small mr-1">#{{ $bill['userId'] }}</span>
                        {{ $bill['lastName'] }}, {{ $bill['firstName'] }}
                    </a>
                </td>
                <td class="text-right">{{ format_money($bill['sum']) }}</td>
                <td class="text-right" width="100">
                    <a
                        href="preview/{{ $bill['userId'] }}"
                        class="btn btn-sm btn-outline-secondary py-0 align-text-top"
                        target="_blank"
                    >Vorschau</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" class="font-weight-bold text-right lead">
                {{ format_money($bills->sum('sum')) }}
            </td>
            <td></td>
        </tr>
        </tfoot>
    </table>
    @endif
@endsection
