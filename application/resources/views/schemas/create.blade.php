@extends('layouts.sidebar')

@section('title')
    Abrechnungs&shy;formel Erstellen
@endsection

@section('main-content')
    <form action="{{ route('schemas.store') }}" method="POST">
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
                    ],
                    [
                        'type' => 'text',
                        'label' => __('Formula'),
                        'name' => 'formula',
                        'required' => true,
                    ],
                ]
            ])

            @include('schemas.partials.hint')

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Schema Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
