@extends('layouts.sidebar')

@section('title', $count . ' Provisionspakete')

@section('actions')
    @can('create', \App\Models\BonusBundle::class)
        <a href="{{ route('commissions.bundles.create') }}" class="btn btn-primary btn-sm">
            Packet Erstellen
        </a>
    @endcan
@endsection

@section('main-content')
    @card
        @slot('title', 'Aktiv')
        @slot('info')
            Derzeit auswählbar von Partnern nach der Regis&shy;trierung.
            Der hier angegebene Name wird bei der Auswahl als Überschrift angezeigt.
        @endslot

        @isset($bundles[1])
            @foreach($bundles[true] as $bundle)
            <div class="p-2 shadow-sm border rounded @unless($loop->first) mt-3 @endunless">
                <h5 class="text-dark">{{ $bundle->name }}</h5>
                @include('components.bundle-editor', ['bonuses' => $bundle->bonuses])
            </div>
            @endforeach
        @else
            <div class="lead text-center text-muted d-flex justify-content-center align-items-center flex-fill h-100">
                Noch keine Pakete angelegt
            </div>
        @endisset
    @endcard

    <h4>Intern / Versteckt / Archiv</h4>
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @isset($bundles[0])
            @foreach($bundles[false] as $bundle)
            <tr>
                <td>{{ $bundle->name }}</td>
                <td>{{ $bundle->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2" class="text-muted text-center">
                    Noch keine Pakete archiviert
                </td>
            </tr>
        @endisset
        </tbody>
    </table>

@endsection
