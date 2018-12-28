@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Investments
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Investments'
        ])
    @endif
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-sm
                  table-hover table-striped table-sticky position-relative">
        <thead>
            <tr>
                <th class="text-right">ID</th>
                <th>Name</th>
                <th>Projekt</th>
                <th>Provisionstyp</th>
                <th class="text-right">Betrag</th>
                <th class="text-right">Investmentdatum</th>
                <th class="text-right">Bezahldatum</th>
            </tr>
        </thead>
        <tbody>
        @forelse($investments as $investment)
            @php($cancelled = $investment->isCancelled())
            <tr>
                <td class="text-right text-muted small align-middle">{{ $investment->id }}</td>
                <td>{{ $investment->name }}</td>
                <td>{{ $investment->project_name }}</td>
                <td>{{ $investment->type }}</td>
                <td class="text-right">
                    @unless($cancelled)
                    {{ format_money($investment->amount) }}
                    @endif
                </td>
                <td class="text-right">
                    {{ ($investment->created_at)->format('d.m.Y') }}
                </td>
                <td class="text-right">
                    @if($cancelled)
                    <div class="text-muted">Storniert</div>
                    @else
                    {{ optional($investment->paid_at)->format('d.m.Y') }}
                    @endif
                </td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="6">Es wurden noch keine Investments getätigt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
