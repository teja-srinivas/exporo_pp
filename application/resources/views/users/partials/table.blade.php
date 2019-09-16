@include('components.table', ['data' => [
    'rows' => $users->values(),
    'totalAggregates' => false,
    'columns' => [
        [
            'name' => 'user',
            'label' => 'Benutzer',
            'format' => 'user',
        ],
        [
            'name' => 'company',
            'label' => 'Firma',
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'align' => 'right',
            'width' => 100,
        ],
        [
            'name' => 'roles',
            'label' => 'Rolle',
            'format' => 'roles',
            'width' => 90,
            'align' => 'right',
            'options' => [
                'roles' => $roles,
            ],
        ],
        [
            'name' => 'createdAt',
            'label' => 'Datum',
            'format' => 'date',
            'width' => 40,
        ],
    ],
    'defaultSort' => [
        'name' => 'createdAt',
        'order' => 'desc',
    ],
]])
