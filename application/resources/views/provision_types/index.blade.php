@extends('layouts.sidebar')

@section('title', $provisionType->count() . ' provisionta')

@section('actions')
    @can('create', App\ProvisionType::class)
        <a href="{{ route('provisionTypes.create') }}" class="btn btn-primary btn-sm">Provisionstyp Erstellen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($provisionType as $provision)
            <tr>
                <td><a href="{{ route('provisionTypes.show', $provision) }}">{{ $provision->name }}</a></td>
                <td>@timeago($provision->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Abrechnungsprovisionta erstellt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
