<?php
$bonuses = $bonuses ?? [];
$options = [
    'bonuses' => is_array($bonuses) ? $bonuses : $bonuses->values(),
];

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
