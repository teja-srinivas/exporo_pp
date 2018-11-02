@extends('layouts.sidebar')

@section('title', $bundles->count() . ' Provisionspackete')

@section('actions')
    @can('create', \App\Models\BonusBundle::class)
        <a href="{{ route('commissions.bundles.create') }}" class="btn btn-primary btn-sm">
            Packet Erstellen
        </a>
    @endcan
@endsection

@section('main-content')
    @foreach($bundles as $bundle)
    @card
        @slot('title', $bundle->name)
        @slot('info', $bundle->selectable ? 'Zur Auswahl von Partnern möglich' : '')
        @include('components.bundle-editor', ['bonuses' => $bundle->bonuses])
    @endcard
    @endforeach
@endsection
