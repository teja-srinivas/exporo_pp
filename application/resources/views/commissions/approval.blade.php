@extends('layouts.sidebar')

@section('title')
    Provisionsübersicht
@endsection

@section('actions')
    <div class="lead">
        <span class="font-weight-bold">{{ $totals['count'] }}</span>
        Provision{{ $totals['count'] === 1 ? '' : 'en' }} offen
    </div>
@endsection

@section('main-content')
    @php($vueData = [
        'api' => route('api.commissions.index'),
        'totals' => $totals,
    ])
    <vue data-is="commission-approval" data-props='@json($vueData)' />
@endsection
