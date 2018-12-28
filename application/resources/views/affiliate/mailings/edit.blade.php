@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        route('affiliate.mails.index') => 'Mailings',
        route('affiliate.mails.show', $mailing) => $mailing->title,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('affiliate.mails.update', $mailing) }}" method="POST">
        @method('PUT')
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
                        'help' => 'Folgende Textbausteine stehen zur verfügung:<br><code>#partnername</code> für "Vorname Nachname"<br><code>#reflink</code> für "?a_aid=&lt;benutzerid&gt;"'
                    ],
                ],
            ])

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