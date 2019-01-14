@extends('layouts.sidebar')

@section('title', $projects->count() . ' Projekte')

@section('main-content')
    @include('components.table', ['data' => [
        'groupable' => true,
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
                'width' => 25,
                'align' => 'center',
            ],
            [
                'name' => 'date',
                'label' => 'Erstellt',
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
