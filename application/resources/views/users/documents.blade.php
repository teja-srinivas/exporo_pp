@extends('layouts.sidebar')

@section('title', 'Dokumente')

@section('main-content')
    <table class="table bg-white table-borderless table-striped shadow-sm" style="line-height: 1rem">
        <tbody>
        @forelse($documents as $document)
            <tr>
                <td @empty($document['meta'])colspan="{{ $detailColumns + 1 }}"@endempty>
                    <p class="small text-muted mb-1">{{ $document['type'] }}</p>
                    <span class="lead font-weight-bold">
                        <a href="{{ $document['link'] }}">{{ $document['title'] }}</a>
                    </span>
                </td>
                @unless(empty($document['meta']))
                @foreach([
                    'commissions' => 'Provisionen',
                    'net' => 'Summe',
                ] as $key => $displayName)
                    @isset($document['meta'][$key])
                        <td class="text-right">
                            <p class="small text-muted mb-1">{{ $displayName }}</p>
                            <div class="lead">{{ $document['meta'][$key] }}</div>
                        </td>
                    @elseif($loop->index < $detailColumns)
                        <td></td>
                    @endif
                @endforeach
                @endunless
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
