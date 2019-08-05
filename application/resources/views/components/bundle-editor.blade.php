<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

$bonuses = $bonuses ?? [];
$options = [
    'bonuses' => ($bonuses instanceof Arrayable) ? $bonuses->toArray() : $bonuses,
];

if (isset($api)) {
    $options['api'] = $api;
}

if (($editable ?? null) === false) {
    $options['editable'] = false;
}

if (($legacy ?? null) === true) {
    $options['legacy'] = true;
}

if (($bundle ?? null) !== null) {
    $options['bundle'] = ($bundle instanceof Model) ? $bundle->getKey() : $bundle;
}

?>

<vue
    data-is="bonus-bundle-editor"
    data-props='@json($options + $defaults)'
>
    <div class="text-center text-muted">
        Provisionsschema wird geladen&hellip;
    </div>
</vue>
