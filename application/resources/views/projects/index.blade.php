@extends('layouts.sidebar')

@section('title', $projects->count() . ' Projekte')

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>
                    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                </td>
                <td>
                    @unless($project->wasApproved())
                        <div class="badge badge-warning">Ausstehend</div>
                    @endunless
                </td>
                <td>@timeago($project->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Projekte angelegt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
