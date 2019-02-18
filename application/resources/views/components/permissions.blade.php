@php($skipPermissions = collect())

@foreach($model->roles->load('permissions')->sortBy('name') as $role)
    <div class="form-group">
        <b>
            Rolle:
            <a href="{{ route('roles.show', $role) }}">
                {{ $role->getDisplayName() }}
            </a>
        </b>

        <ul class="small pl-4">
            @foreach($role->permissions->sortBy('name') as $permission)
                <li
                    @if($skipPermissions->contains($permission->getKey()))
                    class="text-muted"
                    style="text-decoration: line-through"
                    @endif
                >
                    {{ $permission->name }}
                </li>

                @php($skipPermissions->push($permission->getKey()))
            @endforeach
        </ul>
    </div>
@endforeach

<div class="form-group">
    <b>Eigene:</b>

    <div class="pl-2">
        {{-- Do not skip the permissions we have access to --}}
        @php($skipPermissions = $skipPermissions->diff($model->getDirectPermissions()->modelKeys()))

        @foreach($permissions->whereNotIn('id', $skipPermissions) as $permission)
            <input type="hidden" name="permissions[{{ $permission->getKey() }}]">

            @include('components.form.checkbox', [
                'label' => $permission->name,
                'name' => "permissions[{$permission->getKey()}]",
                'default' => $model->can($permission->name),
            ])
        @endforeach
    </div>
</div>
