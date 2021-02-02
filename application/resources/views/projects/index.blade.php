@extends('layouts.sidebar')

@section('title', $projects->count() . ' Projekte')

@section('main-content')
    @php($data = [
        'tableData' => [
            'groupable' => true,
            'selectable' => true,
            'selectLabel' => 'Iframe',
            'totalAggregates' => false,
            'rows' => $projects->values(),
            'columns' => [
                [
                    'name' => 'inSelection',
                    'label' => 'iFrame',
                    'width' => '80',
                ],
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
        ],
        'selection' => $selection,
        'api' => route('api.projects.updateMultiple'),
    ])
    <vue v-cloak class="cloak-fade" data-is="projects-container" data-props='@json($data)'></vue>
@endsection
