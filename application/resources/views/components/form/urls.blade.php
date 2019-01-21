@php($data = [
    'values' => old($name, $default ?? []),
    'errors' => $errors->get("{$name}.*"),
    'name' => $name,
])

<vue data-is="url-input" data-props='@json($data)'></vue>
