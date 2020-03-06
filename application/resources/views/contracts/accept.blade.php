@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <form method="POST" class="col-md-8">
                @csrf

                @card
                @slot('title', "Ihre persönliche Tippgebervereinbarung mit Exporo")
                @include('contracts.partials.header', compact('contract'))

                <div class="my-4">
                    @component('components.form.checkbox', [
                        'name' => 'legal_agb',
                    ])
                        Ich habe die
                        <a href="{{ \App\Models\Agb::current(\App\Models\Agb::TYPE_GMBH)->getDownloadUrl() }}" target="_blank">
                            AGB der Exporo Investment GmbH
                        </a>
                        heruntergeladen und gelesen
                    @endcomponent

                    @component('components.form.checkbox', [
                        'name' => 'legal_contract',
                    ])
                        Ich habe die Unterlagen zur <a href="{{ $pdfPartner }}" target="_blank">Tippgebervereinbarung</a> gelesen
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
                    Ich habe die <a href="{{ $pdfProduct }}" target="_blank">Provisionsvereinbarungen</a>
                    gelesen und verstanden,
                    dass diese als Anhang der Tippgebervereinbarung zu verstehen sind
                    und keiner Bestätigung durch mich erfordern.
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
