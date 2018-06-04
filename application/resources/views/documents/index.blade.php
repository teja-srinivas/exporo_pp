@extends('layouts.sidebar')

@section('title', $documents->count() . ' Dokumente')

@section('actions')
    @can('create', App\Document::class)
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Benutzer</th>
            <th width="160">Datum</th>
        </tr>
        </thead>
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td><a href="{{ route('documents.show', $document) }}">{{ $document->name }}</a></td>
                <td>
                    <a href="{{ route('users.show', $document->user) }}">
                        {{ $document->user->first_name }}
                        {{ $document->user->last_name }}
                    </a>
                </td>
                <td>{{ $document->created_at->format('d.m.Y H:i:s') }}</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="4">Noch keine Dokumente hochgeladen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
