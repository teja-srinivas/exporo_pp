<?php

use App\Http\Resources\CommissionBonus;
use Illuminate\Database\Eloquent\Model;

$options = [
    'bonuses' => empty($bonuses) ? [] : CommissionBonus::collection($bonuses),
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

?>

<vue
    data-is="bonus-bundle-editor"
    data-props='@json($options + $defaults)'
>
    <div class="text-center text-muted">
        Provisionsschema wird geladen&hellip;
    </div>
</vue>
