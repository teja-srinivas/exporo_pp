@include('components.table', ['data' => [
    'rows' => $users->values(),
    'totalAggregates' => false,
    'columns' => array_values(array_filter([
        [
            'name' => 'user',
            'label' => 'Benutzer',
            'format' => 'user',
        ],
        $users->isNotEmpty() && array_key_exists('company', $users->first()) ? [
            'name' => 'company',
            'label' => 'Firma',
        ] : null,
        [
            'name' => 'status',
            'label' => 'Status',
            'align' => 'right',
            'width' => 110,
        ],
        [
            'name' => 'roles',
            'label' => 'Rolle',
            'format' => 'roles',
            'align' => 'right',
            'options' => [
                'roles' => $roles,
            ],
        ],
        [
            'name' => 'createdAt',
            'label' => 'Datum',
            'format' => 'date',
            'width' => 60,
        ],
    ])),
    'defaultSort' => [
        'name' => 'createdAt',
        'order' => 'desc',
    ],
]])

@php($showDetails = (bool) request()->get('user_table_details', false))
<a href="?user_table_details={{ (int) (!$showDetails) }}" class="btn btn-sm btn-secondary">
    Mehr Details {{ $showDetails ? 'verstecken' : 'anzeigen' }}
</a>
