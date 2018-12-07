@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Meine Subpartner
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Subpartner'
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
            <th class="text-right">Kunden</th>
            <th class="text-right">Investments</th>
            <th class="text-right">Volumen</th>
            <th class="text-right">Provision</th>
            <th class="text-right">Angenommen am</th>
        </tr>
        </thead>
        <tbody>
        @forelse($children as $child)
            <tr>
                <td class="text-right text-muted small align-middle">{{ $child->id }}</td>
                <td>{{ $child->display_name }}</td>
                <td class="text-right">{{ $child->investors }}</td>
                <td class="text-right">{{ $child->investments }}</td>
                <td class="text-right">{{ format_money($child->amount) }}</td>
                <td class="text-right">{{ format_money($child->commissions) }}</td>
                <td class="text-right">
                    {{ optional($child->accepted_at)->format('d.m.Y') }}
                </td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="7">Es wurden noch keine Subpartner geworben</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
