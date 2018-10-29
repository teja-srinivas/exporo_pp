@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('authorization.index') => 'Berechtigungen',
        'Rollen',
        $role->getDisplayName(),
    ])
@endsection

@section('actions')
    @if($role->canBeDeleted() && auth()->user()->can('delete', $role))
        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $role)
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @card
        @slot('title', 'Fähigkeiten')
        @slot('info', 'die Benutzer mit dieser Rolle besitzen')

        @forelse($role->permissions as $permission)
            <span class="badge badge-light">{{ $permission->name }}</span>
        @empty
            <div class="text-muted text-center">Keine Fähigkeiten zugewiesen</div>
        @endforelse
    @endcard

    @include('users.partials.table', ['users' => $role->users])
    @include('roles.partials.details')
@endsection
