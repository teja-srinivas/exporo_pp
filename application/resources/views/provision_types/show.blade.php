@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
    route('provisionTypes.index') => 'Provisionstypen',
    $provisionType->name,
    ])
@endsection

@section('actions')
    @unless($provisionType->projects()->count() > 0)
        <form action="{{ route('provisionTypes.destroy', $provisionType) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $provisionType)
        <a href="{{ route('provisionTypes.edit', $provisionType) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    <h4 class="mt-4">Projekte mit diesem provisionType</h4>

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
                <td>{{ $project->name }}</td>
                <td class="text-right">@timeago($project->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Diese provisionType wurde noch von keinem Projekt verwendet</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection