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

            @php($canEditAnything = false)

            <h6 class="mb-2 text-uppercase tracking-wide">Mehrwertsteuer</h6>

            @can('management.contracts.update-vat-details')
                @php($canEditAnything = true)
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
            @else
                <div class="row">
                    <div class="col-sm-6">{{ __('Betrag in Prozent') }}:</div>
                    <div class="col-sm-6">{{ $contract->vat_amount }}%</div>
                </div>
                <div class="row">
                    <div class="col-sm-6">{{ __('Berechnung') }}:</div>
                    <div class="col-sm-6">{{ $contract->vat_included ? 'Inkludiert' : 'On Top' }}</div>
                </div>
            @endcan

            <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Sondervereinbarung</h6>
            @can('management.contracts.update-special-agreement')
                @php($canEditAnything = true)
                <textarea name="special_agreement" class="form-control" rows="3">{{
                    old('special_agreement', $contract->special_agreement)
                }}</textarea>
            @else
                <span>{{ $contract->special_agreement ?? '(Keine)' }}</span>
            @endcan

            @if($canEditAnything)
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">
                    Änderungen speichern
                </button>
            </div>
            @endif
        </form>
        @endcard
    </div>
</div>

<div class="rounded bg-white shadow-sm p-3 my-3">
    @include('components.bundle-editor', [
        'api' => route('api.contracts.bonuses.store', [$contract]),
        'bonuses' => $contract->bonuses,
        'editable' => Auth::user()->can('management.contracts.commission-bonuses.update'),
        'extras' => [
            'contract_id' => $contract->getKey(),
        ],
    ])
</div>
