@extends('layouts.sidebar')

@section('title', $list->count() . ' AGBs')

@section('actions')
    @can('create', \App\Models\Agb::class)
        <a href="{{ route('agbs.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th width="50">Aktiv</th>
            <th width="170">Kategorie</th>
            <th>Name</th>
            <th width="115" class="text-right">Akzeptiert</th>
            <th width="100" class="text-right pr-3">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        @forelse($list as $agb)
            <tr>
                <td class="text-center" style="vertical-align: middle">{!! $agb->is_default ? '★' : '' !!}</td>
                <td class="small" style="vertical-align: middle">{{ __("agbs.type.{$agb->type}") }}</td>
                <td><a href="{{ route('agbs.show', $agb) }}">{{ $agb->name }}</a></td>
                <td class="text-right">
                    @if($agb->users > 0)
                        <strong>{{ $agb->users }} <small>Benutzer</small></strong>
                    @else
                        <span class="text-muted">&mdash;</span>
                    @endif
                </td>
                <td class="text-right">
                    <a href="{!! $agb->getDownloadUrl() !!}"
                       class="btn btn-link btn-xs {{ empty($agb->filename) ? 'disabled' : '' }}">PDF Anzeigen</a>
                </td>
            </tr>
        @empty
            <tr class="text-center text-muted">
                <td colspan="4">Noch keine AGBs hochgeladen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
