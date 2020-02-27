@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <form method="POST" class="col-md-8">
                @csrf

                @card
                @slot('title', "Ihr persönlicher Partnervertrag mit Exporo")
                @include('contracts.partials.header', compact('contract'))

                <div class="my-4">
                    @component('components.form.checkbox', [
                        'name' => 'legal_agb',
                    ])
                        Ich habe die
                        <a href="{{ \App\Models\Agb::current(\App\Models\Agb::TYPE_GMBH)->getDownloadUrl() }}" target="_blank">
                            AGBs der Exporo Investment GmbH
                        </a>
                        heruntergeladen und gelesen
                    @endcomponent

                    @component('components.form.checkbox', [
                        'name' => 'legal_contract',
                    ])
                        Ich habe die Unterlagen zum <a href="{{ $pdfPartner }}" target="_blank">Partnervertrag</a> gelesen
                    @endcomponent
                </div>

                <div class="my-4">
                    @include('components.bundle-editor', [
                        'bonuses' => $bonuses,
                        'editable' => false,
                        'legacy' => true,
                    ])
                </div>

                <p>
                    Ich habe die <a href="{{ $pdfProduct }}" target="_blank">Provisionsvereinbarung</a>
                    gelesen und verstanden,
                    dass diese als Anhang des Partnervertrages zu verstehen ist
                    und keiner Bestätigung durch mich erfordert.
                </p>

                <p>
                    Bitte geben Sie zur Bestätigung und digiten Unterschrift
                    Ihren vollständigen Vor- und Zunamen ein:
                </p>

                @include('components.form.builder', [
                    'labelWidth' => 3,
                    'inputs' => [
                        [
                            'type' => 'text',
                            'label' => 'Digitale Unterschrift',
                            'name' => 'signature',
                            'help' => 'Beispiel: Max Mustermann',
                        ],
                    ],
                ])

                @slot('footer')
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="dismiss" value="true" class="btn btn-secondary">
                            Abbrechen
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Akzeptieren
                        </button>
                    </div>
                @endslot
                @endcard
            </form>
        </div>
    </div>
@endsection
