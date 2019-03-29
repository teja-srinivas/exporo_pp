@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4>Ihr persönlicher Partnervertrag mit Exporo</h4>

                @card
                    @include('contracts.partials.header', ['user' => $contract->user])
                @endcard

                @card
                    @slot('title', 'Produkte und Provisionssätze, die sie dafür erhalten')

                    @include('components.bundle-editor', [
                        'bonuses' => $contract->bonuses,
                        'editable' => false,
                    ])
                @endcard

                @card
                    @component('components.form.checkbox', ['name' => 'legal_contract', 'required' => true])
                        Ich habe die Partnervertragunterlagen gelesen und ausgedruckt und/oder gespeichert.
                    @endcomponent

                    @include('components.form.error', ['name' => 'legal_contract'])

                    @component('components.form.checkbox', ['name' => 'legal_eula', 'required' => true])
                        Ich habe die AGBs der Exporo Investment GmbH heruntergeladen und gelesen.
                    @endcomponent

                    @include('components.form.error', ['name' => 'legal_eula'])
                @endcard
            </div>
        </div>
    </div>
@endsection
