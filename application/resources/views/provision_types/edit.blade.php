@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
    route('provisionTypes.index') => 'Provisionstypen',
    route('provisionTypes.show', $provisionType) => $provisionType->name,
    'Bearbeiten',
    ])
@endsection

@section('main-content')

    <form action="{{ route('provisionTypes.update', $provisionType) }}" method="POST">
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
                    'default' => $provisionType->name,
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
