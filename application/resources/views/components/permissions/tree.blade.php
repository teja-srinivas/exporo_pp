<?php

use App\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

// Put all permissions into a nested tree structure
$grouped = [];

/** @var Collection $permissions */
$permissions->each(function (Permission $permission) use (&$grouped) {
    Arr::set($grouped, $permission->name, $permission);
});

?>

@foreach($grouped as $key => $group)
    @include('components.permissions.branch', [
        'hidden' => $hidden ?? false,
        'model' => $model ?? null,
        'permission' => $group,
    ])
@endforeach
