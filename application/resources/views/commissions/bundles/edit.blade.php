@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('commissions.bundles.index') => 'Provisionspakete',
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <div class="rounded shadow-sm bg-white">
        @include('components.bundle-editor', [
            'bonuses' => $bundle->bonuses,
        ])
    </div>

    <form action="{{ route('commissions.bundles.update', $bundle) }}" method="POST">
        @method('PUT')
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                    'default' => $bundle->name,
                ],
                [
                    'type' => 'checkbox',
                    'label' => __('Typ'),
                    'name' => 'selectable',
                    'description' => 'Für Partner zur Auswahl möglich',
                    'default' => $bundle->selectable,
                ],
            ]
        ])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Änderungen Speichern</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
