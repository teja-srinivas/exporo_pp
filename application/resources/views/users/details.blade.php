@component('components.model.details', ['model' => $user])
    @component('components.model.detail', ['title' => 'Status'])
        @foreach ($user->roles as $role)
            <a href="#" class="badge badge-pill badge-{{ App\User::getRoleColor($role) }}">
                {{ studly_case($role->name) }}
            </a>
        @endforeach
    @endcomponent
@endcomponent
