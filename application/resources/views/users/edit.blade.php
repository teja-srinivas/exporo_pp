@extends('layouts.sidebar')

@section('title')
    @if($user->is(auth()->user()))
        Benutzerinformationen
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => "{$user->first_name} {$user->last_name}",
            'Bearbeiten'
        ])
    @endif
@endsection

@section('main-content')
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

    @include('users.partials.details')

    @include('components.audit', ['model' => [$user, $user->details]])
@endsection
