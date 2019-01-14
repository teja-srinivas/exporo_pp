@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Meine Kunden
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Investoren'
        ])
    @endif
@endsection

@section('main-content')
    @include('components.table', ['data' => [
        'rows' => $investors->values(),
        'columns' => [
            [
                'name' => 'id',
                'label' => 'ID',
                'align' => 'right',
                'small' => true,
                'width' => 35,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
            ],
            [
                'name' => 'investments',
                'label' => 'Investments',
                'format' => 'number',
                'width' => 55,
            ],
            [
                'name' => 'amount',
                'label' => 'Volumen',
                'format' => 'currency',
                'width' => 75,
            ],
            [
                'name' => 'activationAt',
                'label' => 'Aktiviert am',
                'format' => 'date',
                'width' => 55,
            ],
        ],
    ]])
@endsection
