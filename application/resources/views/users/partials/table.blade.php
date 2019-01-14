@include('components.table', ['data' => [
    'rows' => $users->values(),
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
            'width' => 50,
        ],
        [
            'name' => 'roles',
            'label' => 'Rolle',
            'format' => 'roles',
            'width' => '10%',
            'align' => 'right',
            'options' => [
                'roles' => $roles,
            ],
        ],
        [
            'name' => 'createdAt',
            'label' => 'Datum',
            'format' => 'date',
            'width' => 65,
        ],
    ],
    'defaultSort' => [
        'name' => 'user',
        'order' => 'asc',
    ],
]])
