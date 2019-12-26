@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('main-content')
    @php($vueData = [
        'api' => route('api.dashboard.investments'),
        'investmentsTwelveMonths' => $investments,
    ])
    <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
@endsection
