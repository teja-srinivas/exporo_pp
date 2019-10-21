@component('users.partials.contracts.component', ['contract' => $contract])
    Subpartner: {{ $contract->hasOverhead() ? 'Ja' : 'Nein' }}

    <span class="mx-1">&bull;</span>

    MwSt:
    @if ($contract->vat_amount > 0)
        {{ $contract->vat_amount }}%
        {{ $contract->vat_included ? 'Inkl.' : 'On Top' }}
    @else
        Nein
    @endif
@endcomponent
