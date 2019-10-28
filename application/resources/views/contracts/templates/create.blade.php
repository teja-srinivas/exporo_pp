@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('contracts.templates.index') => 'Vertragsvorlagen',
        __("contracts.{$type}.title"),
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route("contracts.templates.{$type}.store") }}" method="POST">
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 4,
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
                    'name' => 'is_default',
                    'label' => 'Standard bei Registrierung',
                    'help' => 'Bei Neuregistrierungen wird automatisch ein Vetragsentwurf aus dieser Vorlage angelegt.'
                ],
            ]
        ])

        @include("contracts.templates.create.{$type}")

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Vorlage Erstellen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
