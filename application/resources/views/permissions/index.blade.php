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
            </tr>
            </thead>
            <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Rollen angelegt</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

    @card
        @slot('title', 'Fähigkeiten')
        @slot('info', 'Rollen speichern eine Liste von "Fähigkeiten", die ein Benutzer besitzt, sofern er einer Rolle zugewiesen ist.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @forelse($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
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
