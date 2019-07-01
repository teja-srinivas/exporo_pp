<input
    id="input{{ studly_case($name) }}"
    type="{{ $type ?? 'text' }}"
    class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ $class ?? '' }}"
    name="{{ $name }}"
    value="{{ old($name, $default ?? null) }}"
    @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @isset($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required ?? false) required @endif
    @if($autofocus ?? false) autofocus @endif
>

@include('components.form.error', [
    'name' => $name,
    'error' => $error ?? null,
    'class' => '',
])
