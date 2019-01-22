@extends('layouts.sidebar')

@section('title', $projects->count() . ' Projekte')

@section('main-content')
    @include('components.table', ['data' => [
        'groupable' => true,
        'totalAggregates' => false,
        'rows' => $projects->values(),
        'columns' => [
            [
                'name' => 'name',
                'label' => 'Name',
                'link' => 'links.self',
                'width' => '36%',
            ],
            [
                'name' => 'schema',
                'label' => 'Schema',
                'groupBy' => true,
            ],
            [
                'name' => 'type',
                'label' => 'Typ',
                'groupBy' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'groupBy' => true,
                'width' => 40,
                'align' => 'center',
            ],
            [
                'name' => 'launchedAt',
                'label' => 'Fundingstart',
                'format' => 'date',
                'width' => 90,
            ],
            [
                'name' => 'createdAt',
                'label' => 'Erstellt',
                'format' => 'date',
                'width' => 75,
            ],
            [
                'name' => 'updatedAt',
                'label' => 'Updated',
                'format' => 'date',
                'width' => 75,
            ],
        ],
        'defaultSort' => [
            'name' => 'name',
            'order' => 'asc',
        ],
    ]])
@endsection
