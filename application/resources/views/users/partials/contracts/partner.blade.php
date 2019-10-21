@component('users.partials.contracts.component', ['contract' => $contract])
    Kündigungsfrist: {{ trans_choice('time.days', $contract->cancellation_days) }}

    <span class="mx-1">&bull;</span>

    Anspruch: {{ trans_choice('time.years', $contract->claim_years) }}
@endcomponent
