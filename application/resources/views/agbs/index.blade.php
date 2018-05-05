@extends('layouts.sidebar')

@section('title')
    <div class="d-flex justify-content-between align-items-center">
        <div>AGBs</div>
        <a href="{{ route('agbs.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    </div>
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky">
        <thead>
        <tr>
            <th width="90">Standard</th>
            <th>Name</th>
            <th class="text-right">Akzeptiert</th>
            <th width="100" class="text-right">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        @forelse($list as $agb)
            <tr>
                <td class="text-center">{!! $agb->is_default ? '★' : '' !!}</td>
                <td><a href="{{ route('agbs.show', $agb) }}">{{ $agb->name }}</a></td>
                <td class="text-right">
                    @if($agb->users->isEmpty())
                        <span class="text-muted">&mdash;</span>
                    @else
                        <strong>{{ $agb->users->count() }} <small>Benutzer</small></strong>
                    @endif
                </td>
                <td class="text-right">
                    @if($agb->canBeDeleted())
                    <form action="{{ route('agbs.destroy', $agb) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-link btn-xs">Löschen</button>
                    </form>
                    @endif
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
