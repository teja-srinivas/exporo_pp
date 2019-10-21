@card
    @slot('title', 'Produktvertrag')
    @include('components.bundle-editor', [
        'bonuses' => $contract->bonuses,
        'editable' => false,
        'legacy' => true,
    ])
@endcard
