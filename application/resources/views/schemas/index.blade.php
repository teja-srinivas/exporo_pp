@extends('layouts.sidebar')

@section('title', $schemas->count() . ' Schemata')

@section('actions')
    @can('create', \App\Models\Schema::class)
        <a href="{{ route('schemas.create') }}" class="btn btn-primary btn-sm">Abrechnungsschema Erstellen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Formel</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($schemas as $schema)
            <tr>
                <td><a href="{{ route('schemas.show', $schema) }}">{{ $schema->name }}</a></td>
                <td>{{ $schema->formula }}</td>
                <td>{{ optional($schema->created_at)->format('d.m.Y') }}</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Abrechnungsschemata erstellt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
