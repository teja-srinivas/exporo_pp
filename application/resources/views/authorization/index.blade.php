@extends('layouts.sidebar')

@section('title', 'Berechtigungen')

@section('main-content')
    @card
        @slot('title', 'Rollen')
        @slot('info', 'Benutzer können zwar mehreren Rollen zugewiesen sein, allerdings ist nur eine erforderlich.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
                <th width="100" class="text-right">Aktionen</th>
            </tr>
            </thead>
            <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>
                        <a href="{{ route('roles.show', $role) }}">{{ $role->getDisplayName() }}</a>

                        @unless($role->canBeDeleted())
                            <div class="badge badge-info">System</div>
                        @endunless
                    </td>
                    <td class="text-right">
                        <a href="{{ route('roles.edit', $role) }}"
                           class="btn btn-xs btn-link {{ auth()->user()->can('update', $role) ? '' : 'disabled' }}">
                            Ändern
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Rollen angelegt</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @slot('footer')
            <div class="text-right">
                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Neu Anlegen</a>
            </div>
        @endslot
    @endcard

    @card
        @slot('title', 'Fähigkeiten')
        @slot('subtitle', 'Rollen speichern eine Liste von "Fähigkeiten", die ein Benutzer besitzt, sofern er einer Rolle zugewiesen ist.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th width="50%">Name</th>
                <th width="50%">Rollen</th>
            </tr>
            </thead>
            <tbody>
            @forelse($permissions as $permission)
                <tr>
                    <td>{{
                        join(' › ', array_map(function (string $key) {
                            return __("permissions.$key");
                        }, explode('.', $permission->name)))
                    }}</td>
                    <td>
                        @foreach($permission->roles->sortBy(function ($role) {
                            return mb_strtolower($role->name);
                        }) as $role)
                            <a href="{{ route('roles.show', $role) }}" class="badge badge-primary">{{
                                $role->getDisplayName()
                            }}</a>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Fähigkeiten angelegt</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard
@endsection
