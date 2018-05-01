@extends('layouts.sidebar')

@section('main-content')
    <h3 class="mb-3">Provisionsübersicht</h3>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-2">
        <div class="card-body py-3">
            <div class="row text-center">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Ausstehend
                    <div class="h2 mb-1">{{ formatMoney(0, 0) }}</div>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Freigegeben
                    <div class="h2 mb-1">{{ formatMoney(0, 0) }}</div>
                </div>
                <div class="col-sm">
                    Ausgezahlt
                    <div class="h2 mb-1">{{ formatMoney(0, 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    @card
        @slot('title', 'Vermitteltes Kapital')
        @slot('subtitle', 'freigegebener Provisionen')

        <!-- TODO -->
        <div class="p-5" style="height: 420px"></div>
    @endcard
@endsection
