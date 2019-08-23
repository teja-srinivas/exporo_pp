@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('schemas.index') => 'Abrechnungsformeln',
        route('schemas.show', $schema) => $schema->name,
        'Bearbeiten',
    ])
@endsection

@section('main-content')

    <form action="{{ route('schemas.update', $schema) }}" method="POST">
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
                        'default' => $schema->name,
                    ],
                    [
                        'type' => 'text',
                        'label' => __('Formula'),
                        'name' => 'formula',
                        'required' => true,
                        'default' => $schema->formula,
                    ],
                ]
            ])

            @include('schemas.partials.hint')

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen Speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('schemas.partials.details')
@endsection
