@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4>Ihr persönlicher Partnervertrag mit Exporo</h4>

                @include('components.status')

                @card
                    @include('contracts.partials.header', ['user' => $contract->user])
                @endcard

                <div class="rounded bg-white shadow-sm p-3">
                    @include('components.bundle-editor', [
                        'bonuses' => $contract->bonuses,
                        'editable' => false,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
