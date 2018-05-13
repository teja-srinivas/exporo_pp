@extends('layouts.sidebar')

@section('main-content')
    <h3>Dokumente</h3>

    <table class="table bg-white table-borderless table-striped shadow-sm">
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td>
                    <strong>{{ $document['type'] }}</strong>
                    <h5><a href="{{ $document['link'] }}">{{ $document['title'] }}</a></h5>
                </td>
                <td class="align-middle text-right" width="200">
                    <abbr title="{{ $document['created_at']}}">
                        {{ \Jenssegers\Date\Date::make($document['created_at'])->diffForHumans() }}
                    </abbr>
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
