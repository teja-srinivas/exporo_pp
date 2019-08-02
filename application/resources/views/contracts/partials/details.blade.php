@component('components.model.details', ['model' => $contract])
    @component('components.model.detail', ['title' => 'Freigegeben'])
        {{ optional($contract->released_at)->format('d.m.Y') }}
    @endcomponent

    @component('components.model.detail', ['title' => 'Akzeptiert'])
        {{ optional($contract->accepted_at)->format('d.m.Y') }}
    @endcomponent
@endcomponent
