<input
    id="input{{ studly_case($name) }}"
    type="{{ $type ?? 'text' }}"
    class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
    name="{{ $name }}"
    value="{{ old($name, $default ?? null) }}"
    @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @isset($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required ?? false) required @endif
>

@include('components.form.error', compact('name', 'error'))
