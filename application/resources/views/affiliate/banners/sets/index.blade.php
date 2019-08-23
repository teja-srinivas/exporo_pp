@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
        'Banner',
    ])
@endsection

@section('actions')
    @can('create', \App\Models\BannerSet::class)
        <a href="{{ route('banners.sets.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    @forelse($sets as $set)
        <a href="{{ route('banners.sets.show', $set) }}"
           class="d-block p-3 rounded bg-white shadow-sm my-2 lead font-weight-bold leading-sm">
            {{ $set->title }} <small class="text-muted ml-2">{{ $set->banners_count }} Banner</small>
        </a>
    @empty
        <div class="p-3 rounded bg-white shadow-sm my-2 text-muted text-center">
            <p>Noch keine Banner-Sets angelegt</p>
            <a href="{{ route('banners.sets.create') }}" class="btn btn-primary">Jetzt anlegen</a>
        </div>
    @endforelse
@endsection
