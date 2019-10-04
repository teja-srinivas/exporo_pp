@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        'Banner',
    ])
@endsection

@section('main-content')
    @card
        Hier finden Sie Banner, die Sie auf Ihrer Website platzieren können.
    @endcard

    @php($data = ['sets' => $sets])
    <vue v-cloak class="cloak-fade" data-is="banner-viewer" data-props='@json($data)' />
@endsection
