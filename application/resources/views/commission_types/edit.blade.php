@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
    route('commissionTypes.index') => 'Provisionstypen',
    route('commissionTypes.show', $type) => $type->name,
    'Bearbeiten',
    ])
@endsection

@section('main-content')

    <form action="{{ route('commissionTypes.update', $type) }}" method="POST">
        @csrf
        @method('PUT')

        @card
        @include('components.form.builder', [
            'labelWidth' => 2,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                    'default' => $type->name,
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
