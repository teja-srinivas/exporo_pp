{{-- TODO add permission to view this? --}}
<div class="my-3 p-2 border text-muted small d-flex flex-wrap justify-content-between">
    {{ $slot ?? '' }}

    @component('components.model.detail', ['title' => 'Erstellt'])
        @timeago($model->created_at)
    @endcomponent

    @component('components.model.detail', ['title' => 'Aktualisiert'])
        @timeago($model->updated_at)
    @endcomponent
</div>
