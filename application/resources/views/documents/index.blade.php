@extends('layouts.sidebar')

@section('title', $documents->count() . ' Dokumente')

@section('actions')
    @can('create', \App\Models\Document::class)
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Benutzer</th>
            <th width="140">Erstellt Am</th>
        </tr>
        </thead>
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td><a href="{{ route('documents.show', $document) }}">{{ $document->name }}</a></td>
                <td>
                    <a href="{{ route('users.show', $document->user) }}">
                        {{ $document->user->getDisplayName() }}
                    </a>
                </td>
                <td>{{ optional($document->created_at)->format('d.m.Y') }}</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="3">Noch keine Dokumente hochgeladen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
