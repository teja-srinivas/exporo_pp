@php($data = [
    'values' => old($name, $default ?? []),
    'errors' => $errors->get("{$name}.*"),
    'name' => $name,
])

<vue data-is="variable-input" data-props='@json($data)'></vue>
