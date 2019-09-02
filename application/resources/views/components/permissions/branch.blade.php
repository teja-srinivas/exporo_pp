@if(is_array($permission))
    {{-- To not clutter the screen with permission switches, we only open the relevant ones --}}
    @if(($isOpen ?? null) === null)
        @php($doOpen = \Illuminate\Support\Arr::first($permission, function($entry) use ($model) {
            /** @var null|\Spatie\Permission\Traits\HasPermissions $model */
            /** @var array|\App\Models\Permission $entry */
            return is_array($entry) || ($model !== null && $model->hasDirectPermission($entry->name));
        }) !== null)
    @endif

    <details {{ ($doOpen ?? $isOpen) ? 'open' : '' }} @unless($loop->last) class="mb-1" @endif>
        <summary>
            <strong>{{ __("permissions.$key") }}</strong>
        </summary>

        <div class="ml-1 pl-3 border-left @unless($loop->last) mb-3 @endif">
            @foreach(collect($permission)->sortBy(function ($value, string $key) {
                // Place nested groups before switches by prefixing them with a number
                // so they appear higher for the lexicographical ordering
                return (is_array($value) ? 1 : '') . strtolower(__("permissions.$key"));
            }, SORT_NATURAL) as $key => $group)
                @include('components.permissions.branch', [
                    'hidden' => $hidden ?? false,
                    'isOpen' => $isOpen ?? null,
                    'model' => $model ?? null,
                    'permission' => $group,
                ])
            @endforeach
        </div>
    </details>
@else
    @include('components.form.checkbox', [
        'label' => __("permissions.$key"),
        'name' => "permissions[{$permission->getKey()}]",
        'default' => $model !== null && $model->hasDirectPermission($permission->name),
        'design' => 'switch',
    ])
@endif
