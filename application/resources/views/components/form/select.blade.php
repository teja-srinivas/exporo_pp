@php($oldValue = old($name, $default ?? null))
@php($associative = $assoc ?? false)

<select
    class="custom-select{{ $errors->has($name) ? ' is-invalid' : '' }} w-auto"
    id="input{{ studly_case($name) }}"
    name="{{ $name }}"
    @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @if($required ?? false) required @endif
>
    <option value="" {{ $oldValue ? '' : 'selected' }}>
        {{ $emptyText ?? '—' }}
    </option>

    @foreach($values as $key => $value)
    <option
        value="{{ $associative ? $key : $value }}"
        {{ $oldValue === $value ? 'selected' : '' }}
    >
        {{ $value }}
    </option>
    @endforeach
</select>

@include('components.form.error', compact('name', 'error'))
