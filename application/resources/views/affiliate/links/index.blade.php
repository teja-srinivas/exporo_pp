@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        'Links',
    ])
@endsection

@section('actions')
    @can('create', \App\Models\Link::class)
        <a href="{{ route('affiliate.links.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    @card
        Hier finden Sie verschiedene Links, die Sie verwenden können, um auf Exporo auf die aktuellen Immobilienprojekte
        aufmerksam zu machen. Sie können diese einfach kopieren und mit anderen teilen.

        <a href="#links">Zu den Links &raquo;</a>
    @endcard

    @include('affiliate.links.partials.dashboard')

    <div id="links">
    @forelse($links as $link)
        <div class="p-3 rounded bg-white shadow-sm my-2">
            <div class="lead font-weight-bold mb-2 leading-sm d-flex align-items-baseline">
                <div class="mr-2">{{ $link['title'] }}</div>

                @if(auth()->user()->can('delete', $link))
                    <form action="{{ route('affiliate.links.destroy', $link) }}" method="POST" class="d-inline-flex mx-1">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">Löschen</button>
                    </form>
                @endif

                @can('update', $link)
                    <a href="{{ route('affiliate.links.edit', $link) }}" class="btn btn-outline-primary btn-sm mx-1">Bearbeiten</a>
                @endcan
            </div>
            <span class="leading-sm text-muted">{{ $link['description'] }}</span>
            <input type="text" readonly class="form-control mt-3 rounded border-0 shadow-none" value="{{ $link->userInstance }}">
        </div>
    @empty
        <div class="p-3 rounded bg-white shadow-sm my-2 text-muted text-center">
            Keine Links zur Auswahl
        </div>
    @endforelse
    </div>
@endsection
