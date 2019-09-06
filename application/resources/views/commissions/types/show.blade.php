@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
    route('commissions.types.index') => 'Provisionstypen',
    $type->name,
    ])
@endsection

@section('actions')
    @can('delete', $type)
        <form action="{{ route('commissions.types.destroy', $type) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endcan

    @can('update', $type)
        <a href="{{ route('commissions.types.edit', $type) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
@if($type->is_project_type)
    <h4 class="mt-4">Projekte mit diesem Provisionstyp</h4>

    @include('components.table', ['data' => [
        'rows' => $projects,
        'totalAggregates' => false,
        'columns' => [
            [
                'name' => 'project',
                'label' => 'Projekt',
                'link' => 'links.self',
                'width' => '100%',
            ],
            [
                'name' => 'date',
                'label' => 'Erstellt',
                'format' => 'date',
                'width' => 75,
            ],
        ],
        'defaultSort' => [
            'name' => 'project',
            'order' => 'asc',
        ],
    ]])
@else
    <p class="lead text-center">
        Dieser Provisionstyp ist nicht projektbezogen
    </p>
@endif
@endsection
