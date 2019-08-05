<textarea
    id="input{{ Str::studly($name) }}"
    class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ $class ?? '' }}"
    name="{{ $name }}"
    @isset($rows) rows="{{ $rows }}" @endif
    @isset($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required ?? false) required @endif
    @if($autofocus ?? false) autofocus @endif
>{{ old($name, $default ?? null) }}</textarea>

@include('components.form.error', [
    'name' => $name,
    'error' => $error ?? null,
])
