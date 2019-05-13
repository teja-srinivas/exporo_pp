@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('commissions.bundles.index') => 'Provisionspakete',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('commissions.bundles.store') }}" method="POST">
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'contained' => false,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Auswahl'),
                    'name' => 'selectable',
                    'description' => 'Für Partner zur Auswahl möglich',
                    'default' => true,
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Subpartner'),
                    'name' => 'child_user_selectable',
                    'description' => 'Für Sub-Partner zur Auswahl möglich',
                    'default' => true,
                    'help' => 'Partner, die geworben wurden und somit eine Subpartner-Beziehung haben, bekommen eine andere Auswahl.'
                ],
            ]
        ])

        @include('components.bundle-editor', ['ajax' => false])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Packet Erstellen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
