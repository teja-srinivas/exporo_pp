@extends('layouts.sidebar')

@section('title', $types->count() . ' Provisionstypen')

@section('actions')
    @can('create', \App\Models\CommissionType::class)
        <a href="{{ route('commissions.types.create') }}" class="btn btn-primary btn-sm">Provisionstyp Erstellen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Art</th>
            <th width="140">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($types as $type)
            <tr>
                <td>
                    <a href="{{ route('commissions.types.show', $type) }}">{{ $type->name }}</a>
                </td>
                <td>
                    @if($type->is_project_type)
                    <div class="badge badge-info">Projektbezogen</div>
                    @endif
                    @if($type->is_public)
                    <div class="badge badge-info">Öffentlich</div>
                    @endif
                </td>
                <td>{{ optional($type)->created_at->format('d.m.Y') }}</td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="5">Noch keine Provisionstypen erstellt</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
