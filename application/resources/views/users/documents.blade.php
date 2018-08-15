@extends('layouts.sidebar')

@section('title', 'Dokumente')

@section('main-content')
    <table class="table bg-white table-borderless table-striped shadow-sm" style="line-height: 1rem">
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td>
                    <p class="small text-muted mb-1">{{ $document['type'] }}</p>
                    <span class="lead font-weight-bold">
                        <a href="{{ $document['link'] }}">{{ $document['title'] }}</a>
                    </span>
                </td>
                <td class="align-middle text-right" width="200">
                    <p class="small text-muted mb-1">Erstellt</p>
                    @timeago($document['created_at'])
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-muted text-center">Keine Dokumente zum Anzeigen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
