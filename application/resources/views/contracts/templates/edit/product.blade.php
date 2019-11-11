<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Mehrwertsteuer</h6>

@include('components.form.builder', [
    'labelWidth' => 4,
    'contained' => false,
    'inputs' => [
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
    ]
])

<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Provisionsschema</h6>
@include('components.bundle-editor', [
    'bonuses' => $template->bonuses,
    'api' => route('api.contracts.templates.bonuses.store', $template),
])
