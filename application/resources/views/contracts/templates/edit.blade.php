@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('contracts.templates.index') => 'Vertragsvorlagen',
        'Bearbeiten',
    ])
@endsection

@section('actions')
    @can('delete', $template)
        <form action="{{ route('contracts.templates.destroy', $template) }}" method="POST">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endcan
@endsection

@section('main-content')
    <form action="{{ route('contracts.templates.update', $template) }}" method="POST">
        @method('PUT')
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 4,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                    'default' => $template->name,
                ],
                [
                    'type' => 'number',
                    'label' => __('Anspruch Kundenbindung'),
                    'name' => 'claim_years',
                    'required' => true,
                    'default' => $template->claim_years,
                    'help' => 'In Jahren'
                ],
                [
                    'type' => 'number',
                    'label' => __('Kündigungsfrist'),
                    'name' => 'cancellation_days',
                    'required' => true,
                    'default' => $template->cancellation_days,
                    'help' => 'In Tagen'
                ],
                [
                    'type' => 'number',
                    'name' => 'vat_amount',
                    'label' => 'Betrag in Prozent',
                    'default' => $template->vat_amount,
                ],
                [
                    'type' => 'radio',
                    'name' => 'vat_included',
                    'label' => 'Berechnung',
                    'default' => $template->vat_included,
                    'values' => [
                        false => 'On Top',
                        true => 'Inkludiert',
                    ],
                ],
                [
                    'type' => 'checkbox',
                    'name' => 'is_default',
                    'label' => 'Standard bei Registrierung',
                    'default' => $template->is_default,
                    'help' => 'Bei Neuregistrierungen wird automatisch ein Vetragsentwurf aus dieser Vorlage angelegt.'
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

    <div class="rounded shadow-sm bg-white">
        @include('components.bundle-editor', [
            'bonuses' => $template->bonuses,
            'api' => route('api.contracts.templates.bonuses.store', $template),
        ])
    </div>
@endsection
