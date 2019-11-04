@include('components.form.builder', [
    'labelWidth' => 4,
    'contained' => false,
    'inputs' => [
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
            'type' => 'radio',
            'name' => 'is_exclusive',
            'label' => __('Exklusiv für Exporo tätig'),
            'default' => $template->is_exclusive,
            'values' => [
                false => 'Nein',
                true => 'Ja',
            ],
        ],
        [
            'type' => 'radio',
            'name' => 'allow_overhead',
            'label' => __('Erlaubt Subpartner'),
            'default' => $template->allow_overhead,
            'values' => [
                false => 'Nein',
                true => 'Ja',
            ],
        ],
    ]
])
