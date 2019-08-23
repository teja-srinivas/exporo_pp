@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('schemas.index') => 'Abrechnungsschemata',
        $schema->name,
    ])
@endsection

@section('actions')
    @unless($schema->projects()->count() > 0)
    <form action="{{ route('schemas.destroy', $schema) }}" method="POST" class="d-inline-flex">
        @method('DELETE')
        @csrf
        <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
    </form>
    @endif

    @can('update', $schema)
        <a href="{{ route('schemas.edit', $schema) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    <h4 class="mt-4">Projekte mit diesem Schema</h4>

    @include('components.table', ['data' => [
        'rows' => $projects->values(),
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
@endsection
