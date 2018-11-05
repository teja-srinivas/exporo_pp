@extends('layouts.sidebar')

@section('title', $count . ' Provisionspackete')

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
            Der hier angegebene Name ist nur für interne Zwecke da
            und wird nicht mit angezeigt.
        @endslot

        @foreach($bundles[true] as $bundle)
            <div class="p-2 shadow-sm border rounded @unless($loop->first) mt-3 @endunless">
                <h5 class="text-dark">{{ $bundle->name }}</h5>
                @include('components.bundle-editor', ['bonuses' => $bundle->bonuses])
            </div>
        @endforeach
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
        @foreach($bundles[false] as $bundle)
            <tr>
                <td>{{ $bundle->name }}</td>
                <td>@timeago($bundle->created_at)</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
