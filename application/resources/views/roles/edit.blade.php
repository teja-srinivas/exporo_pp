@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('authorization.index') => 'Berechtigungen',
        'Rollen',
        route('roles.show', $role) => $role->getDisplayName(),
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        @card
            @if($role->canBeDeleted())
            @include('components.form.builder', [
                'contained' => false,
                'labelWidth' => 2,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => __('Name'),
                        'name' => 'name',
                        'required' => true,
                        'default' => $role->name,
                    ],
                ]
            ])
            @endif

            <div class="form-group row mb-0">
                <label class="col-sm-2 col-form-label">Fähigkeiten:</label>
                <div class="col-sm-10 pt-1">
                    @foreach($permissions as $permission)
                        @include('components.form.checkbox', [
                            'label' => $permission->name,
                            'name' => "permissions[{$permission->id}]",
                            'default' => $role->hasDirectPermission($permission),
                        ])
                    @endforeach
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen Speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('roles.partials.details')
@endsection
