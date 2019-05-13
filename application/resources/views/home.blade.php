@extends('layouts.sidebar')

@section('title', 'Abrechnungen')

@section('main-content')
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
