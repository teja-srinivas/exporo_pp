{{-- TODO add permission to view this? --}}
<div class="my-3 p-2 border text-muted small d-flex flex-wrap justify-content-between">
    {{ $slot ?? '' }}

    @component('components.model.detail', ['title' => 'Erstellt'])
        {{ $model->created_at->format('d.m.Y') }}
    @endcomponent

    @component('components.model.detail', ['title' => 'Aktualisiert', 'class' => ''])
       {{ $model->updated_at->format('d.m.Y') }}
    @endcomponent
</div>
