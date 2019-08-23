@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('users.index') => 'Benutzer',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('users.store') }}" method="post">
        @csrf

        @card
            @slot('title', __('users.edit.required_information.title'))
            @slot('info', __('users.edit.required_information.info'))

            @include('users.partials.forms.required_information')

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Benutzer anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
