@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Meine Subpartner
    @else
        @breadcrumbs([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Subpartner'
        ])
    @endif
@endsection

@section('main-content')
    @php($data = [
        'rows' => $children,
        'columns' => [
            [
                'name' => 'user',
                'label' => 'Name',
                'format' => 'user',
                'width' => '50%',
            ],
            [
                'name' => 'investors',
                'label' => 'Kunden',
                'format' => 'number',
            ],
            [
                'name' => 'investments',
                'label' => 'Investments',
                'format' => 'number',
                'width' => 90,
            ],
            [
                'name' => 'amount',
                'label' => 'Volumen',
                'format' => 'currency',
                'width' => 120,
            ],
            [
                'name' => 'commissions',
                'label' => 'Provisionen',
                'format' => 'currency',
                'width' => 85,
            ],
            [
                'name' => 'acceptedAt',
                'label' => 'Angenommen',
                'format' => 'date',
                'width' => 100,
                'fallback' => [
                    'null' => '<small class="small text-muted">(Ausstehend)</small>',
                ],
            ],
        ],
    ])
    <vue v-cloak class="cloak-fade" data-is="subuser-table" data-props='@json($data)'></vue>
@endsection
