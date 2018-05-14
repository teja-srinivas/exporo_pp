<div class="my-3 p-2 border text-muted small">
    <div class="row">
        {{ $slot }}

        @component('components.model.detail', ['title' => 'Erstellt'])
            <abbr title="{{ $model->created_at }}">{{ $model->created_at->diffForHumans() }}</abbr>
        @endcomponent

        <div class="col-12 col-md">
            <strong>Zuletzt aktualisiert:</strong>
            <abbr title="{{ $model->updated_at }}">{{ $model->updated_at->diffForHumans() }}</abbr>
        </div>
    </div>
</div>
