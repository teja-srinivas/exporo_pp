@extends('layouts.sidebar')

@section('title')
    Provisionstypen Erstellen
@endsection

@section('main-content')
    <form action="{{ route('provisionTypes.store') }}" method="POST">
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
            ]
        ])

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Provisionstyp Anlegen</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
