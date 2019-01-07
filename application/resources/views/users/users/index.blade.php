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
                'width' => 70,
            ],
            [
                'name' => 'investments',
                'label' => 'Investments',
                'format' => 'number',
                'width' => 100,
            ],
            [
                'name' => 'amount',
                'label' => 'Volumen',
                'format' => 'currency',
                'width' => '130',
            ],
            [
                'name' => 'commissions',
                'label' => 'Provisionen',
                'format' => 'currency',
                'width' => '115',
            ],
            [
                'name' => 'acceptedAt',
                'label' => 'Angenommen am',
                'format' => 'date',
                'width' => 130,
            ],
        ],
    ]])
@endsection
