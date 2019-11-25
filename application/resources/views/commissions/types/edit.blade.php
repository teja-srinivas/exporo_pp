@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
    route('commissions.types.index') => 'Provisionstypen',
    route('commissions.types.show', $type) => $type->name,
    'Bearbeiten',
    ])
@endsection

@section('main-content')

    <form action="{{ route('commissions.types.update', $type) }}" method="POST">
        @csrf
        @method('PUT')

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                    'default' => $type->name,
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Typ'),
                    'name' => 'is_project_type',
                    'default' => $type->is_project_type,
                    'description' => 'Für Projekte zur Auswahl möglich',
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Öffentlich'),
                    'name' => 'is_public',
                    'default' => $type->is_public,
                    'description' => 'Für öffentliche Projekttypen',
                ],
            ]
        ])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Änderungen Speichern</button>
            </div>
        @endslot
        @endcard
    </form>

@endsection
