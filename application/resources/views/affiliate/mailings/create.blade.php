@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        route('affiliate.mails.index') => 'Mailings',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('affiliate.mails.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @card
            @include('components.form.builder', [
                'labelWidth' => 3,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Beschreibung',
                        'name' => 'description',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => 'Text-Inhalt',
                        'required' => true,
                        'name' => 'text',
                        'rows' => 10,
                        'help' => 'Folgende Textbausteine stehen zur verfügung:<br><code>#partnername</code> für "Vorname Nachname"<br><code>#reflink</code> für "?a_aid=&lt;benutzerid&gt;"'
                    ],
                ],
            ])

            @include('affiliate.mailings.partials.file')

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Neu Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
