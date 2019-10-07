@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        route('affiliate.mails.index') => 'Mailings',
        route('affiliate.mails.show', $mailing) => $mailing->title,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('affiliate.mails.update', $mailing) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @card
            @include('components.form.builder', [
                'labelWidth' => 2,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                        'default' => $mailing->title,
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Beschreibung',
                        'name' => 'description',
                        'default' => $mailing->description,
                    ],
                    [
                        'type' => 'textarea',
                        'label' => 'Text-Inhalt',
                        'required' => true,
                        'name' => 'text',
                        'rows' => 10,
                        'default' => $mailing->text,
                        'help' => $__env->make('components.tag-help')->render(),
                    ],
                    [
                        'type' => 'variables',
                        'label' => 'Variablen',
                        'name' => 'variables',
                        'required' => false,
                        'default' => $mailing->variables,
                    ],
                ],
            ])

            @include('affiliate.mailings.partials.file')

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('affiliate.mailings.partials.details')

    @include('components.audit', ['model' => $mailing])
@endsection
