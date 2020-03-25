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
        <div class="lead text-muted">
            Hier lassen sich Kampagnen erstellen, die Nutzern im Partner-Cockpit angezeigt werden.
        </div>
    @endcard

    <table class="bg-white shadow-sm table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $campaign)
                <tr>
                    <td>
                        <a href="{{ route('campaigns.edit', $campaign) }}">
                            {{ $campaign->title }}
                        </a>
                    </td>
                    <td>
                        @if($campaign->is_active)
                            <div class="badge badge-info">aktiv</div>
                        @else
                            <div class="badge badge-warning">inaktiv</div>
                        @endif
                    </td>
                    <td>
                      {{ optional($campaign)->created_at->format('d.m.Y') }}
                    </td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="5">
                        <p>Noch keine Kampagnen angelegt</p>
                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Jetzt anlegen</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
