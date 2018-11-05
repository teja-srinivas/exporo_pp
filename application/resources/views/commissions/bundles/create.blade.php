@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('commissions.bundles.index') => 'Provisionspackete',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('commissions.bundles.store') }}" method="POST">
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'contained' => false,
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
                    'name' => 'selectable',
                    'description' => 'Für Partner zur Auswahl möglich',
                    'default' => true,
                ],
            ]
        ])

        @include('components.bundle-editor', ['ajax' => false])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Packet Erstellen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
