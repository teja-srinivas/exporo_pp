@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('contracts.templates.index') => 'Vertragsvorlagen',
        __("contracts.{$template->type}.title"),
        'Bearbeiten',
    ])
@endsection

@section('actions')
    @can('delete', $template)
        <form action="{{ route('contracts.templates.destroy', $template) }}" method="POST">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endcan
@endsection

@section('main-content')
    <form action="{{ route("contracts.templates.{$template->type}.update", $template) }}" method="POST">
        @method('PUT')
        @csrf

        @card
        @include('components.form.builder', [
            'labelWidth' => 4,
            'contained' => false,
            'inputs' => [
                [
                    'type' => 'text',
                    'label' => __('Name'),
                    'name' => 'name',
                    'required' => true,
                    'default' => $template->name,
                ],
                [
                    'type' => 'checkbox',
                    'name' => 'is_default',
                    'label' => 'Standard bei Registrierung',
                    'default' => $template->is_default,
                    'help' => 'Bei Neuregistrierungen wird automatisch ein Vetragsentwurf aus dieser Vorlage angelegt.'
                ],
            ]
        ])

        @include("contracts.templates.edit.{$template->type}")

        @slot('footer')
            <div class="text-right">
                <button class="btn btn-primary">Änderungen Speichern</button>
            </div>
        @endslot
        @endcard
    </form>
@endsection
