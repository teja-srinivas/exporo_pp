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
        @endcard

        @card
            @slot('title', __('users.edit.user_details.title'))
            @slot('info', __('users.edit.user_details.info'))

            @include('users.partials.forms.user_details')

            @can('manage', $user)
                <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Verträge</h6>

                @include('components.form.builder', ['inputs' => [
                    [
                        'type' => 'select',
                        'name' => 'contract',
                        'label' => __('Contract Types'),
                        'values' => $contractTemplates,
                        'assoc' => true,
                    ],

                ]])
            @endcan

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Benutzer anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
