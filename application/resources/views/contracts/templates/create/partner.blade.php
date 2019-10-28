@include('components.form.builder', [
    'labelWidth' => 4,
    'contained' => false,
    'inputs' => [
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
    ]
])
