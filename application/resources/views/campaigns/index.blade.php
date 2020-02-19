@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
        'Kampagnen',
    ])
@endsection

@section('actions')
    @can('create', \App\Models\Campaign::class)
        <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    @card
        <div class="lead text-center text-muted">
            Hier etwas Text
        </div>
    @endcard

    @forelse($campaigns as $campaign)
        <a href="{{ route('campaigns.show', $campaigns) }}"
           class="d-block p-3 rounded bg-white shadow-sm my-2 lead font-weight-bold leading-sm">
            {{ $campaigns->title }}
        </a>
    @empty
        <div class="p-3 rounded bg-white shadow-sm my-2 text-muted text-center">
            <p>Noch keine Kampagnen angelegt</p>
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Jetzt anlegen</a>
        </div>
    @endforelse
@endsection
