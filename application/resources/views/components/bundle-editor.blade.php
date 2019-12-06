<?php

use App\Http\Resources\CommissionBonus;

$options = [
    'bonuses' => empty($bonuses) ? [] : CommissionBonus::collection($bonuses),
];

if (isset($api)) {
    $options['api'] = $api;
}

if (($editable ?? null) === false) {
    $options['editable'] = false;
}

if (isset($showPublicity)) {
    $options['showPublicity'] = $showPublicity;
}

if (($legacy ?? null) === true) {
    $options['legacy'] = true;
}

?>

<vue
    v-cloak class="cloak-fade"
    data-is="bonus-bundle-editor"
    data-props='@json($options + $defaults)'
>
    <div class="text-center text-muted">
        Provisionsschema wird geladen&hellip;
    </div>
</vue>
