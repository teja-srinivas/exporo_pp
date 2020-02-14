@extends('layouts.sidebar')

@section('title', 'Übersicht')

@section('main-content')
    @php($vueData = [
        'apiInvestments' => route('api.dashboard.investments'),
        'apiCommissions' => route('api.dashboard.commissions'),
    ])
    <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
@endsection
