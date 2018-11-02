@php($bonuses = $bonuses ?? [])

<vue
    data-is="bonus-bundle-editor"
    data-props='@json($defaults + ['bonuses' => is_array($bonuses) ? $bonuses : $bonuses->values()])'
/>
