@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        route('banners.sets.index') => 'Banner',
        $set->title,
    ])
@endsection

@section('actions')
    @if(auth()->user()->can('delete', $set))
        <form action="{{ route('banners.sets.destroy', $set) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $set)
        <a href="{{ route('banners.sets.edit', $set) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @php($dropzone = [
        'id' => 'banner-upload',
        'duplicateCheck' => true,
        'options' => [
            'url' => route('banners.store', $set),
            'headers' => [
                'X-CSRF-TOKEN' => csrf_token(),
            ],
        ],
    ])

    <vue data-is="vue-dropzone" data-props='@json($dropzone)'
         class="border-0 shadow-sm rounded p-2"></vue>

    @foreach($set->banners->chunk(2) as $chunk)
        <div class="my-3">
            <div class="row">
                @foreach($chunk as $banner)
                <div class="col-md-6">
                    <img src="{{ $banner->getDownloadUrl() }}" class="img-thumbnail bg-white">
                    <div class="small text-center text-muted">{{ $banner->width }}x{{ $banner->height }}</div>
                </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @include('affiliate.banners.sets.partials.details')
@endsection
