@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4>Ihr persönlicher Partnervertrag mit Exporo</h4>

                @card
                    @include('contracts.partials.header', ['user' => $contract->user])
                @endcard

                <div class="rounded bg-white shadow-sm p-3">
                    @include('components.bundle-editor', [
                        'bonuses' => $contract->bonuses,
                        'editable' => false,
                    ])
                </div>

                @card
                    <table class="table table-sm m-0 border-bottom">
                        <tbody>
                            <tr>
                                <td>Anspruch Kundenbindung</td>
                                <td>
                                    {{ trans_choice('time.years', $contract->claim_years) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Kündigungsfrist</td>
                                <td>
                                    {{ trans_choice('time.days', $contract->cancellation_days) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Subpartner-Berechtigungen</td>
                                <td>
                                    {{ $contract->hasOverhead() ? 'Ja' : 'Nein' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @unless(empty($contract->special_agreement))
                        <h6>Sonderregelungen</h6>
                        <p>{{ $contract->special_agreement }}</p>
                    @endunless
                @endcard
            </div>
        </div>
    </div>
@endsection
