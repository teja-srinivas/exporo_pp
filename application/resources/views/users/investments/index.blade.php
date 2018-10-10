@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Partner-Investments
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Partner-Investments'
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
                <th>Project</th>
                <th>Provisionstyp</th>
                <th class="text-right">Betrag</th>
                <th class="text-right">Bezahldatum</th>
            </tr>
        </thead>
        <tbody>
        @foreach($investments as $investment)
            <tr>
                <td class="text-right text-muted small align-middle">{{ $investment->id }}</td>
                <td>
                    @php($firstName = trim($investment->first_name))
                    @php($lastName = trim($investment->last_name))

                    @if(!empty($firstName) && !empty($lastName))
                        {{ $firstName[0] }}.
                        {{ $lastName }}
                    @elseif(!empty($firstName))
                        {{ $firstName }}
                    @else
                        {{ $lastName }}
                    @endif
                </td>
                <td>{{ $investment->project_name }}</td>
                <td>{{ $investment->type }}</td>
                <td class="text-right">{{ format_money($investment->amount) }}</td>
                <td class="text-right">
                    {{ optional($investment->paid_at)->format('d.m.Y') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
