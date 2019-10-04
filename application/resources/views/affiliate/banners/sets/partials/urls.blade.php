@php($data = [
    'values' => old('urls', $urls ?? []),
    'errors' => $errors->get('urls.*'),
])

<vue data-is="url-input" data-props='@json($data)'></vue>
