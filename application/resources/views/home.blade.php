@extends('layouts.sidebar')

@section('title', 'Abrechnung')

@section('main-content')
    <div class="card shadow-sm border-0 accent-primary mb-2">
        <div class="card-body py-3">
            <div class="row text-center">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Ausstehend
                    <div class="h2 mb-1">{{ format_money(max(0, $pending)) }}</div>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    Freigegeben
                    <div class="h2 mb-1">{{ format_money($approved) }}</div>
                </div>
                <div class="col-sm">
                    Ausgezahlt
                    <div class="h2 mb-1">{{ format_money($paid) }}</div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Auszahlungen</h4>

    @include('components.table', ['data' => [
        'rows' => $bills->values(),
        'columns' => [
            [
                'name' => 'name',
                'label' => 'Name',
                'format' => 'display',
                'options' => [
                    'name' => 'displayName',
                ],
                'link' => 'links.download',
            ],
            [
                'name' => 'date',
                'label' => 'Erstellt',
                'format' => 'date',
                'width' => 70,
            ],
        ],
    ]])
@endsection
