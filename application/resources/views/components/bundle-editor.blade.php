@php($bonuses = $bonuses ?? [])
@php($options = [
    'bonuses' => is_array($bonuses) ? $bonuses : $bonuses->values(),
])

<vue
    data-is="bonus-bundle-editor"
    data-props='@json($defaults + $options)'
/>
