@extends('layouts.sidebar')

@section('title', 'Provisionsübersicht')

@section('main-content')
    <div class="card shadow-sm border-0 accent-primary mb-2">
        <div class="card-body py-3">
            <div class="row text-center">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Ausstehend
                    <div class="h2 mb-1">{{ format_money(0) }}</div>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Freigegeben
                    <div class="h2 mb-1">{{ format_money(0) }}</div>
                </div>
                <div class="col-sm">
                    Ausgezahlt
                    <div class="h2 mb-1">{{ format_money($paid) }}</div>
                </div>
            </div>
        </div>
    </div>

    @card
        @slot('title', 'Vermitteltes Kapital')
        @slot('subtitle', 'freigegebener Provisionen')

        <div class="pb-5 d-flex align-items-center justify-content-center" style="height: 420px">
            <span class="lead text-muted">Es wurden noch keine Provisionen freigegeben/ausgezahlt</span>
        </div>
    @endcard
@endsection
