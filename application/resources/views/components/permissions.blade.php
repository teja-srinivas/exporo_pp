@php($skipPermissions = collect())

@foreach($model->roles->load('permissions')->sortBy('name') as $role)
    <details {!! $loop->last ? 'class="form-group"' : '' !!}>
        <summary class="font-weight-bold">
            Rolle:
            <a href="{{ route('roles.show', $role) }}">
                {{ $role->getDisplayName() }}
            </a>
        </summary>

        <ul class="small pl-4">
            @php($displayNames = $role->permissions->mapWithKeys(function (\App\Models\Permission $permission) {
                $key = join(' › ', array_map(function (string $key) {
                    return __("permissions.$key");
                }, explode('.', $permission->name)));

                return [$key => $permission];
            })->sortKeys())

            @foreach($displayNames as $displayName => $permission)
                <li
                    @if($skipPermissions->contains($permission->getKey()))
                    class="text-muted"
                    style="text-decoration: line-through"
                    @endif
                >
                    {{ $displayName }}
                </li>

                @php($skipPermissions->push($permission->getKey()))
            @endforeach
        </ul>
    </details>
@endforeach

<div class="form-group">
    <b>Eigene:</b>

    <div class="pl-2">
        {{-- Do not skip the permissions we have access to --}}
        @php($skipPermissions = $skipPermissions->diff($model->getDirectPermissions()->modelKeys()))

        @include('components.permissions.tree', [
            'hidden' => true,
            'model' => $model,
            'permissions' => $permissions->whereNotIn('id', $skipPermissions),
        ])
    </div>
</div>
