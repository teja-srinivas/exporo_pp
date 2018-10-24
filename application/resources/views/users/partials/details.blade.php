@component('components.model.details', ['model' => $user])
    @component('components.model.detail', ['title' => 'Status'])
        @foreach ($user->roles as $role)
            <a href="{{ route('roles.show', $role) }}"
               class="badge badge-pill badge-{{ \App\Models\User::getRoleColor($role) }}">
                {{ studly_case($role->name) }}
            </a>
        @endforeach
    @endcomponent
@endcomponent
