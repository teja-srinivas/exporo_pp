@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        'Embed',
    ])
@endsection

@section('main-content')
    @card
        Hier finden Sie Embeds, die Sie auf Ihrer Website platzieren können.
    @endcard

    @php($data = ['links' => $links])
    <vue v-cloak class="cloak-fade" data-is="embed-viewer" data-props='@json($data)' />
@endsection
