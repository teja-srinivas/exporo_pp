@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('schemas.index') => 'Abrechnungsschemata',
        $schema->name,
    ])
@endsection

@section('actions')
    @unless($schema->projects()->count() > 0)
    <form action="{{ route('schemas.destroy', $schema) }}" method="POST" class="d-inline-flex">
        @method('DELETE')
        @csrf
        <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
    </form>
    @endif

    @can('update', $schema)
        <a href="{{ route('schemas.edit', $schema) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    <h4 class="mt-4">Projekte mit diesem Schema</h4>

    <table class="bg-white shadow-sm accent-primary table table-borderless table-striped table-sm table-sticky">
        <thead>
        <tr>
            <th>Projekt</th>
            <th class="text-right" width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td><a href="{{ route('projects.show', $project) }}">{{ $project->description }}</a></td>
                <td class="text-right">@timeago($project->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Diese Schema wurde noch von keinem Projekt verwendet</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
