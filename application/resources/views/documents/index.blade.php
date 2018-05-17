@extends('layouts.sidebar')

@section('title', 'Dokumente')

@section('main-content')
    <table class="table bg-white table-borderless table-striped shadow-sm">
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td>
                    <strong>{{ $document['type'] }}</strong>
                    <h5><a href="{{ $document['link'] }}">{{ $document['title'] }}</a></h5>
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
