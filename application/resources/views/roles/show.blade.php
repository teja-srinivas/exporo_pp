@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
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
        @slot('subtitle', 'die Benutzer mit dieser Rolle besitzen:')

        <div class="row">

        @forelse($role->permissions->sortBy('name')->split(2) as $chunk)
            <div class="col-md-6 small">
                <ul class="pl-3 mb-0">
                @foreach($chunk as $permission)
                    <li>{{
                        join(' › ', array_map(function (string $key) {
                            return __("permissions.$key");
                        }, explode('.', $permission->name)))
                    }}</li>
                @endforeach
                </ul>
            </div>
        @empty
            <div class="text-muted text-center">Keine Fähigkeiten zugewiesen</div>
        @endforelse
        </div>
    @endcard

    @include('users.partials.table', ['users' => $users])
    @include('roles.partials.details')
@endsection
