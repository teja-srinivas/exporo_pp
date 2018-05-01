@extends('layouts.sidebar')

@section('title')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('agbs.index') }}" class="text-muted">AGBs</a>
            <span class="text-muted">/</span>
            {{ $agb->name }}
        </div>
        <a href="{{ route('agbs.edit', $agb) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    </div>
@endsection

@section('main-content')
    @card
        @slot('title', 'Benutzer, die diese AGB akzeptiert haben')
        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Nachname</th>
                <th>Vorname</th>
                <th width="150">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($agb->users->sortBy('last_name') as $user)
                <tr>
                    <td><a href="{{ route('users.show', $user) }}">{{ $user->last_name }}</a></td>
                    <td><a href="{{ route('users.show', $user) }}">{{ $user->first_name }}</a></td>
                    <td>{{ $user->pivot->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch von keinem akzeptiert</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard
@endsection
