@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('agbs.index') => 'AGBs',
        $agb->name,
    ])
@endsection

@section('actions')
    @if($agb->canBeDeleted() && auth()->user()->can('delete', $agb))
        <form action="{{ route('agbs.destroy', $agb) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $agb)
        <a href="{{ route('agbs.edit', $agb) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @card
        @slot('title', 'Benutzer, die diese AGB akzeptiert haben')
        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Nachname</th>
                <th>Vorname</th>
                <th width="140">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($agb->users->sortNatural('last_name') as $user)
                <tr>
                    <td><a href="{{ route('users.show', $user) }}">{{ $user->last_name }}</a></td>
                    <td><a href="{{ route('users.show', $user) }}">{{ $user->first_name }}</a></td>
                    <td>{{ $user->pivot->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch von keinem akzeptiert</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

    @include('agbs.partials.details')
@endsection
