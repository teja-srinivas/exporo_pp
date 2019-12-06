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

                @foreach(\App\Models\Contract::TYPES as $type)
                    @include('components.form.builder', [
                        'contained' => $loop->last,
                        'inputs' => [
                            [
                                'type' => 'select',
                                'name' => "contracts[{$type}]",
                                'label' => __("contracts.{$type}.title"),
                                'values' => $contractTemplates[$type],
                                'assoc' => true,
                                'class' => 'w-100',
                            ],
                        ]
                    ])
                @endforeach
            @endcan

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Benutzer anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
