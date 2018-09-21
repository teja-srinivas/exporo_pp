@extends('layouts.sidebar')

@section('title', $types->count() . ' Provisionstypen')

@section('actions')
    @can('create', App\CommissionType::class)
        <a href="{{ route('commissionTypes.create') }}" class="btn btn-primary btn-sm">Provisionstyp Erstellen</a>
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
        @forelse($types as $type)
            <tr>
                <td><a href="{{ route('commissionTypes.show', $type) }}">{{ $type->name }}</a></td>
                <td>@timeago($type->created_at)</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Abrechnungsprovisionta erstellt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection