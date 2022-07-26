@extends('layouts.sidebar')

@section('title', $bills->count() . ' Abrechnungen')

@section('main-content')
    @include('components.table', ['data' => [
        'selectable' => true,
        'groupable' => true,
        'columns' => [
            [
                'name' => 'name',
                'label' => 'Name',
                'groupBy' => true,
                'format' => 'display',
                'options' => [
                    'name' => 'displayName',
                ],
                'link' => 'links.self',
                'width' => 80,
            ],
            [
                'name' => 'user',
                'label' => 'Benutzer',
                'groupBy' => true,
                'format' => 'user',
                'width' => '50%',
            ],
            [
                'name' => 'gross',
                'label' => 'Summe',
                'format' => 'currency',
                'width' => 100,
            ],
            [
                'name' => 'commissions',
                'groupBy' => true,
                'label' => 'Provisionen',
                'format' => 'number',
                'width' => 75,
            ],
            [
                'name' => 'date',
                'label' => 'Erstellt',
                'format' => 'date',
                'width' => 70,
            ],
        ],
        'groups' => [
            [
                'column' => 'name',
                'sort' => [
                    'name' => 'name',
                    'order' => 'desc',
                ],
            ],
            [
                'column' => 'user',
                'sort' => [
                    'name' => 'user',
                    'order' => 'asc',
                ],
            ],
        ],
        'rows' => $bills->values(),
        'actions' => auth()->user()->can('export', \App\Models\Bill::class) ? [
            [
                'label' => 'XLSX Herunterladen',
                'action' => route('bills.export'),
                'method' => 'get',
            ],
        ] : [],
    ]])
@endsection
