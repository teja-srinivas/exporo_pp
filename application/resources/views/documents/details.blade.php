@component('components.model.details', ['model' => $document])
    @component('components.model.detail', ['title' => 'Benutzer'])
        <a href="{{ route('users.show', $document->user) }}">
            {{ $document->user->first_name }}
            {{ $document->user->last_name }}
        </a>
    @endcomponent
@endcomponent
