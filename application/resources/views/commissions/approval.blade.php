@extends('layouts.sidebar')

@section('title')
    Provisionsübersicht
@endsection

@section('actions')
    <div class="lead">
        <span class="font-weight-bold">{{ $total }}</span>
        Provision{{ $total === 1 ? '' : 'en' }} offen
    </div>
@endsection

@section('main-content')
    <vue data-is="commission-approval" data-props='@json([
        'api' => route('api.commissions.index'),
    ])' />
@endsection
