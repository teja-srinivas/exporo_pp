@extends('layouts.sidebar')

@section('title', 'Übersicht')

@section('main-content')
    @php($vueData = [
        'api' => route('api.dashboard.investments'),
    ])
    <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
@endsection
