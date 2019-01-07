@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Meine Subpartner
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Subpartner'
        ])
    @endif
@endsection

@section('main-content')
    @include('components.table', ['data' => [
        'primary' => 'id',
        'rows' => $children,
        'columns' => [
            [
                'name' => 'user',
                'label' => 'Name',
                'groupBy' => false,
                'format' => 'user',
                'width' => '50%',
            ],
            [
                'name' => 'investors',
                'label' => 'Kunden',
                'format' => 'number',
                'width' => 110,
            ],
            [
                'name' => 'investments',
                'label' => 'Investments',
                'format' => 'number',
                'width' => 140,
            ],
            [
                'name' => 'amount',
                'label' => 'Volumen',
                'groupBy' => false,
                'format' => 'currency',
                'width' => '130',
            ],
            [
                'name' => 'commissions',
                'label' => 'Provisionen',
                'groupBy' => false,
                'format' => 'currency',
                'width' => '115',
            ],
            [
                'name' => 'acceptedAt',
                'label' => 'Angenommen am',
                'groupBy' => false,
                'format' => 'date',
                'width' => 160,
            ],
        ],
    ]])
@endsection
