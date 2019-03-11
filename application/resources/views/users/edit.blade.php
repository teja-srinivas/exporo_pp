@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Benutzerinformationen
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->getDisplayName(),
            'Bearbeiten'
        ])
    @endif
@endsection

@section('main-content')
    @include('users.partials.application')

    <form action="{{ route('users.update', $user) }}" method="POST">
        @method('PUT')
        @csrf

        @card
            @slot('title', __('users.edit.required_information.title'))
            @slot('info', __('users.edit.required_information.info'))

            @include('users.partials.forms.required_information')
        @endcard

        @card
            @slot('title', __('users.edit.user_details.title'))
            @slot('info', __('users.edit.user_details.info'))

            @include('users.partials.forms.user_details')
        @endcard

        <div class="text-right my-3">
            <button class="btn btn-primary">Änderungen Speichern</button>
        </div>
    </form>

    @can('manage', $user)
    <h4>Berechtigungen</h4>

    <form action="{{ route('users.update', $user) }}" method="POST" class="mt-4">
        @method('PUT')
        @csrf

        @card
            @slot('title', 'Benutzerrolle')
            @slot('info', 'Liste von Rollen, zu denen dieser Benutzer zugehörig ist.')

            @foreach(($roles ?? []) as $role)
            <input type="hidden" name="roles[{{ $role->getKey() }}]">

            @include('components.form.checkbox', [
                'label' => $role->getDisplayName(),
                'name' => "roles[{$role->getKey()}]",
                'default' => $user->hasRole($role),
                'design' => 'switch',
            ])
            @endforeach
        @endcard

        @card
            @slot('title', 'Fähigkeiten')
            @slot('info')
                <p>Liste von Fähigkeiten, zu denen dieser Benutzer berechtigt ist.</p>
                Einige Fähigkeiten können durch die Vergabe von Rollen stammen,
                in welchem Fall die Änderung direkt an der Rolle getätigt werden muss
                (welches dann alle zugewiesenen Nutzer betrifft!).
            @endslot

            @include('components.permissions', ['model' => $user])
        @endcard

        <div class="text-right my-3">
            <button class="btn btn-primary">Änderungen Speichern</button>
        </div>
    </form>
    @endcan

    @include('users.partials.details')

    @include('components.audit', ['model' => [$user, $user->details]])
@endsection
