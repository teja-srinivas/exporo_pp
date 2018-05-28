@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('users.index') => 'Benutzer',
        "{$user->first_name} {$user->last_name}",
    ])
@endsection

@section('actions')
    @can('destroy user')
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endcan

    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
@endsection

@section('main-content')
    @card
        @slot('title', 'AGBs')
        @slot('info', 'die von diesem Nutzer akzeptiert wurden.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
                <th width="140">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->agbs as $agb)
                <tr>
                    <td><a href="{{ route('agbs.show', $agb) }}">{{ $agb->name }}</a></td>
                    <td>{{ $agb->pivot->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine AGBs akzeptiert</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

    @include('users.details')
@endsection
