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
    ]
])
