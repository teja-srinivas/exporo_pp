@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        'Übersicht',
    ])
@endsection

@section('main-content')
    @card
        Hier finden Sie eine Übersicht über das Klickverhalten, Conversion-Rates und Co, basierend auf Ihren Maßnahmen mit den von uns bereitgestellten Werbemitteln, wie Banner, Links, Iframes und vielem mehr.
    @endcard

    @php($vueData = [
        'api' => route('api.affiliate-dashboard'),
    ])
    <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
@endsection
