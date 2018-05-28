@extends('layouts.sidebar')

@section('title', 'Dokumente')

@section('main-content')
    <table class="table bg-white table-borderless table-striped shadow-sm" style="line-height: 1rem">
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td>
                    <strong>
                        <span class="text-muted">{{ $document['type'] }}:</span>
                        <a href="{{ $document['link'] }}">{{ $document['title'] }}</a>
                    </strong>
                </td>
                <td class="align-middle text-right" width="200">
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
