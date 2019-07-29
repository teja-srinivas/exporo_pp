@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('users.index') => 'Benutzer',
        route('users.show', $contract->user) => $contract->user->getDisplayName(),
        'Verträge',
        $contract->getKey(),
        'Bearbeiten'
    ])
@endsection

@section('actions')
    <form action="{{ route('contract-status.update', $contract) }}" method="POST" class="d-inline-flex">
        @method('PUT')
        @csrf

        @if($contract->released_at === null)
            <button type="submit" name="release" value="1" class="btn btn-success py-1 px-3">
                Für Partner freigeben
            </button>
        @else
            <button type="submit" name="release" value="0" class="btn btn-danger py-1 px-3">
                Freigabe zurücknehmen
            </button>
        @endif
    </form>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-6">
            @card
            @include('contracts.partials.header', ['user' => $contract->user])
            @endcard
        </div>
        <div class="col-lg-6">
            @card
            <form action="{{ route('contracts.update', $contract) }}" method="POST">
                @method('PUT')
                @csrf

                @include('components.form.builder', [
                    'labelWidth' => 6,
                    'inputs' => [
                        [
                            'type' => 'number',
                            'label' => __('Anspruch Kundenbindung'),
                            'name' => 'claim_years',
                            'required' => true,
                            'default' => $contract->claim_years,
                            'help' => 'In Jahren'
                        ],
                        [
                            'type' => 'number',
                            'label' => __('Kündigungsfrist'),
                            'name' => 'cancellation_days',
                            'required' => true,
                            'default' => $contract->cancellation_days,
                            'help' => 'In Tagen'
                        ],
                    ],
                ])

                <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Mehrwertsteuer</h6>

                @include('components.form.builder', [
                    'labelWidth' => 6,
                    'inputs' => [
                        [
                            'type' => 'number',
                            'name' => 'vat_amount',
                            'label' => 'Betrag in Prozent',
                            'default' => $contract->vat_amount,
                        ],
                        [
                            'type' => 'radio',
                            'name' => 'vat_included',
                            'label' => 'Berechnung',
                            'default' => $contract->vat_included,
                            'values' => [
                                false => 'On Top',
                                true => 'Inkludiert',
                            ],
                        ],
                    ],
                ])

                <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Sondervereinbarung</h6>
                <textarea name="special_agreement" class="form-control" rows="3">{{
                    old('special_agreement', $contract->special_agreement)
                }}</textarea>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">
                        Änderungen speichern
                    </button>
                </div>
            </form>
            @endcard
        </div>
    </div>

    <div class="rounded bg-white shadow-sm p-3 my-3">
        @include('components.bundle-editor', [
            'bonuses' => $contract->bonuses,
        ])
    </div>
@endsection
