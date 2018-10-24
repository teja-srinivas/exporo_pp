@extends('layouts.sidebar')

@section('title')
    Provisionstypen Erstellen
@endsection

@section('main-content')
    <form action="{{ route('commissionTypes.store') }}" method="POST">
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Typ'),
                    'name' => 'is_project_type',
                    'description' => 'Für Projekte zur Auswahl möglich',
                ],
            ]
        ])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Provisionstyp Anlegen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
