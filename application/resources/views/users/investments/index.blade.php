@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Investments
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Investments'
        ])
    @endif
@endsection

@section('main-content')
    @include('components.table', ['data' => [
        'rows' => $investments->values(),
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
                'name' => 'projectName',
                'label' => 'Projekt',
            ],
            [
                'name' => 'type',
                'label' => 'Provisionstyp',
            ],
            [
                'name' => 'amount',
                'label' => 'Betrag',
                'format' => 'currency',
                'width' => 80,
            ],
            [
                'name' => 'createdAt',
                'label' => 'Datum',
                'format' => 'date',
                'width' => 55,
            ],
            [
                'name' => 'paidAt',
                'label' => 'Bezahlt',
                'format' => 'date',
                'width' => 55,
                'align' => 'right',
                'fallback' => [
                    'null' => '',
                    '' => '<small class="small text-muted">(Storniert)</small>',
                ],
            ],
        ],
    ]])
@endsection
