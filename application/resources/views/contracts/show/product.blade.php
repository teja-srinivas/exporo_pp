@card
    @slot('title', $contract->getTitle())
    <table class="table table-sm table-fixed">
        <tr>
            <td>{{ __('Betrag in Prozent') }}:</td>
            <td>{{ $contract->vat_amount }}%</td>
        </tr>
        <tr>
            <td>{{ __('Berechnung') }}:</td>
            <td>{{ $contract->vat_included ? 'Inkludiert' : 'On Top' }}</td>
        </tr>
    </table>

    @include('components.bundle-editor', [
        'bonuses' => $contract->bonuses,
        'editable' => false,
    ])
@endcard
