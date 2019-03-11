<?php

$grouped = [];

/** @var \Illuminate\Support\Collection $permissions */
$permissions->each(function (\App\Models\Permission $permission) use (&$grouped) {
    \Illuminate\Support\Arr::set($grouped, $permission->name, $permission);
});

?>

@foreach($grouped as $key => $group)
    @include('components.permissions.branch', [
        'hidden' => $hidden ?? false,
        'model' => $model ?? null,
        'permission' => $group,
    ])
@endforeach
