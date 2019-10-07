<?php
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;
?>

@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('bills.index') => 'Abrechnungen',
        'Provisionsübersicht',
    ])
@endsection

@section('actions')
    <div class="lead">
        <span class="font-weight-bold">{{ $totals['count'] }}</span>
        Provision{{ $totals['count'] === 1 ? '' : 'en' }} offen
    </div>
@endsection

@section('main-content')
    @if($totals['count'] > 0)
        @php($vueData = [
            'api' => route('api.commissions.index'),
            'totals' => $totals,
            'userDetailsApi' => route('api.users.details.index'),
            'permissions' => [
                'create' => Gate::allows('create', Commission::class),
                'update' => Gate::allows('management.commissions.update'),
                'delete' => Gate::allows('management.commissions.delete'),
            ],
        ])
        <vue v-cloak class="cloak-fade" data-is="commission-approval" data-props='@json($vueData)'></vue>
    @else
        @card
            <div class="lead text-center text-muted">
                Es gibt derzeit keine offenen Provisionen zum Abrechnen
            </div>
        @endcard
    @endif
@endsection
