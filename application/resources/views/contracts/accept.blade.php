@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <form method="POST" class="col-md-8">
                @csrf

                @card
                @slot('title', "Ihr persönlicher Partnervertrag mit {$company->name}")
                @include('contracts.partials.header', compact('contract'))

                <div class="my-4">
                    @include('components.form.checkbox', [
                        'name' => 'legal_agb',
                        'label' => 'Ich habe die AGBs der Exporo Investment GmbH heruntergeladen und gelesen',
                    ])

                    @component('components.form.checkbox', [
                        'name' => 'legal_contract',
                    ])
                        Ich habe <a href="{{ $pdf }}" target="_blank">die Unterlagen zum Partnervertrag</a> gelesen
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
                    Ich habe die Provisionsvereinbarung gelesen und verstanden,
                    dass diese als Anhang des Partnervertrages zu verstehen ist
                    und keiner Bestätigung durch mich erfordert.
                </p>

                <p>
                    Bitte geben Sie zur Bestätigung und digiten Unterschrift
                    Ihren vollständigen Vor- und Zunamen, das heutige Datum und den Ort ein:
                </p>

                @include('components.form.builder', [
                    'labelWidth' => 3,
                    'inputs' => [
                        [
                            'type' => 'text',
                            'label' => 'Unterschrift',
                            'name' => 'signature',
                            'help' => 'Beispiel: Max Mustermann 01.01.2017 Hamburg',
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
