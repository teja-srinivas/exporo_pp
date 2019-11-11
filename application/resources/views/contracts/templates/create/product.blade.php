<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Mehrwertsteuer</h6>

@include('components.form.builder', [
    'labelWidth' => 4,
    'contained' => false,
    'inputs' => [
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

<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Provisionsschema</h6>
@include('components.bundle-editor')
