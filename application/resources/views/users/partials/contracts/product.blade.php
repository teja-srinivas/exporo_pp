@component('users.partials.contracts.component', ['contract' => $contract])
    MwSt:
    @if ($contract->vat_amount > 0)
        {{ $contract->vat_amount }}%
        {{ $contract->vat_included ? 'Inkl.' : 'On Top' }}
    @else
        Nein
    @endif
@endcomponent
