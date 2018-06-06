@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('authorization.index') => 'Berechtigungen',
        'Rollen',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        @card
            @include('components.form.builder', [
                'contained' => false,
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

            <div class="form-group row mb-0">
                <label class="col-sm-2 col-form-label">Fähigkeiten:</label>
                <div class="col-sm-10 pt-1">
                    @foreach($permissions as $permission)
                        @include('components.form.checkbox', [
                            'label' => $permission->name,
                            'name' => "permissions[{$permission->id}]",
                        ])
                    @endforeach
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Rolle Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
