@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('contracts.templates.index') => 'Vertragsvorlagen',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('contracts.templates.store') }}" method="POST">
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
                    'type' => 'number',
                    'label' => __('Anspruch Kundenbindung'),
                    'name' => 'claim_years',
                    'default' => 5,
                    'required' => true,
                    'help' => 'In Jahren'
                ],
                [
                    'type' => 'number',
                    'label' => __('Kündigungsfrist'),
                    'name' => 'cancellation_days',
                    'default' => 1,
                    'required' => true,
                    'help' => 'In Tagen'
                ],
                [
                    'type' => 'number',
                    'name' => 'vat_amount',
                    'default' => 0,
                    'label' => 'Betrag in Prozent',
                ],
                [
                    'type' => 'radio',
                    'name' => 'vat_included',
                    'label' => 'Berechnung',
                    'default' => false,
                    'values' => [
                        false => 'On Top',
                        true => 'Inkludiert',
                    ],
                ],
            ]
        ])

        @include('components.bundle-editor')

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Vorlage Erstellen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
