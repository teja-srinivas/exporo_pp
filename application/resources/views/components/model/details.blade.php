<div class="my-3 p-2 border text-muted small d-flex flex-wrap">
    {{ $slot }}

    @component('components.model.detail', ['title' => 'Erstellt'])
        <abbr title="{{ $model->created_at }}">{{ $model->created_at->diffForHumans() }}</abbr>
    @endcomponent

    @component('components.model.detail', ['title' => 'Aktualisiert'])
        <abbr title="{{ $model->updated_at }}">{{ $model->updated_at->diffForHumans() }}</abbr>
    @endcomponent
</div>
