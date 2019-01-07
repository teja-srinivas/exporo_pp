@extends('layouts.sidebar')

@section('title', $bills->count() . ' Abrechnungen')

@section('actions')
    @can('create', \App\Models\Bill::class)
        <a href="{{ route('commissions.index') }}" class="btn btn-primary btn-sm">Abrechnungen Erstellen</a>
    @endcan
@endsection

@section('main-content')
    @include('components.table', ['data' => [
        'selectable' => true,
        'primary' => 'id',
        'columns' => [
            [
                'name' => 'name',
                'label' => 'Name',
                'format' => 'display',
                'options' => [
                    'name' => 'displayName',
                ],
                'link' => 'links.self',
            ],
            [
                'name' => 'user',
                'label' => 'Benutzer',
                'format' => 'user',
                'width' => '50%',
            ],
            [
                'name' => 'gross',
                'label' => 'Summe',
                'groupBy' => false,
                'format' => 'currency',
            ],
            [
                'name' => 'commissions',
                'label' => 'Provisionen',
                'format' => 'number',
            ],
            [
                'name' => 'date',
                'label' => 'Erstellt',
                'groupBy' => false,
                'format' => 'date',
                'width' => 100,
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
        ],
        'rows' => $bills->values(),
        'actions' => [
            [
                'label' => 'XLSX Herunterladen',
                'action' => route('bills.export'),
                'method' => 'get',
            ],
        ],
    ]])
@endsection
