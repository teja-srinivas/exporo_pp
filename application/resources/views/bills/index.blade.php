@extends('layouts.sidebar')

@section('title', $bills->count() . ' Abrechnungen')

@section('actions')
    @can('create', App\Bill::class)
        <a href="{{ route('commissions.index') }}" class="btn btn-primary btn-sm">Abrechnungen Erstellen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Benutzer</th>
            <th>Summe</th>
            <th>Provisionen</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($bills as $bill)
            <tr>
                <td><a href="{{ $bill['links']['self'] }}">{{ $bill['name'] }}</a></td>
                <td>
                    <a href="{{ $bill['user']['links']['self'] }}">
                        {{ $bill['user']['lastName']}},
                        {{ $bill['user']['firstName']}}
                    </a>
                </td>
                <td class="text-right">{{ $bill['meta']['net'] ?? '' }}</td>
                <td class="text-right">{{ $bill['meta']['commissions'] ?? '' }}</td>
                <td>@timeago($bill['date'])</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Abrechnungen erstellt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
