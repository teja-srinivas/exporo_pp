@component('users.partials.contracts.component', ['contract' => $contract])
    Kündigungsfrist: {{ trans_choice('time.days', $contract->cancellation_days) }}

    <span class="mx-1">&bull;</span>

    Anspruch: {{ trans_choice('time.years', $contract->claim_years) }}

    <span class="mx-1">&bull;</span>

    Subpartner: {{ $contract->allow_overhead ? 'Ja' : 'Nein' }}

    @if($contract->is_exclusive)
        <span class="mx-1">&bull;</span>

        Exklusiv für Exporo tätig
    @endif
@endcomponent
