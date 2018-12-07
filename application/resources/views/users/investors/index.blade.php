@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Meine Kunden
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Investoren'
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
                <th class="text-right">Investments</th>
                <th class="text-right">Volumen</th>
                <th class="text-right">Aktiviert am</th>
            </tr>
        </thead>
        <tbody>
        @forelse($investors as $investor)
            <tr>
                <td class="text-right text-muted small align-middle">{{ $investor->id }}</td>
                <td>
                    {{\App\Traits\Person::anonymizeName(
                        $investor->first_name,
                        $investor->last_name
                    )}}
                </td>
                <td class="text-right">{{ $investor->investments }}</td>
                <td class="text-right">{{ format_money($investor->amount) }}</td>
                <td class="text-right">
                    {{ optional($investor->activation_at)->format('d.m.Y') }}
                </td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="6">Es wurden noch keine Kunden geworben</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
