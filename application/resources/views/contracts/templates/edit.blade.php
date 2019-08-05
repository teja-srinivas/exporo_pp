@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('contracts.templates.index') => 'Vertragsvorlagen',
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
    <div class="rounded shadow-sm bg-white">
        @include('components.bundle-editor', [
            'bonuses' => $template->bonuses,
            'api' => route('api.contracts.templates.index'),
        ])
    </div>

    <form action="{{ route('commissions.bundles.update', $template) }}" method="POST">
        @method('PUT')
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
                    'default' => $template->name,
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
