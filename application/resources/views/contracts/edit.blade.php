@extends('layouts.app')

@section('content')

    <form action="{{ route('contracts.update', $contract) }}" method="POST" class="container">
        @method('PUT')
        @csrf

        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('users.show', $contract->user) }}" class="btn btn-outline-primary btn-sm mb-4">
                    ◀ Zurück zum Nutzer
                </a>

                <h4 class="mb-4">Vertragsbedingungen festlegen</h4>

                @include('components.status')

                @card
                @include('contracts.partials.header', ['user' => $contract->user])
                @endcard

                <div class="rounded bg-white shadow-sm p-3">
                    @include('components.bundle-editor', [
                        'bonuses' => $contract->bonuses,
                    ])
                </div>

                @card
                @include('components.form.builder', [
                    'inputs' => [
                        [
                            'type' => 'number',
                            'label' => __('Anspruch Kundenbindung'),
                            'name' => 'claim_years',
                            'required' => true,
                            'default' => $contract->claim_years,
                        ],
                        [
                            'type' => 'number',
                            'label' => __('Kündigungsfrist'),
                            'name' => 'cancellation_days',
                            'required' => true,
                            'default' => $contract->cancellation_days,
                        ],
                        [
                            'type' => 'textarea',
                            'label' => __('Sondervereinbarung'),
                            'name' => 'special_agreement',
                            'default' => $contract->special_agreement,
                        ],
                    ],
                ])
                @endcard

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        Änderungen speichern
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
