@php($oldValue = old($name, $default ?? null))
@php($associative = $assoc ?? false)

<select
    class="custom-select{{ $errors->has($name) ? ' is-invalid' : '' }} w-auto"
    id="input{{ Str::studly($name) }}"
    name="{{ $name }}"
    @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @if($required ?? false) required @endif
>
    <option value="" {{ $oldValue ? '' : 'selected' }}>
        {{ $emptyText ?? '—' }}
    </option>

    @if($groups ?? false)
        @foreach($values as $group => $items)
            <optgroup label="{{ $group }}">
                @foreach($items as $key => $value)
                    <option
                        value="{{ $associative ? $key : $value }}"
                        {{ $oldValue == ($associative ? $key : $value) ? 'selected' : '' }}
                    >
                        {{ $value }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    @else
        @foreach($values as $key => $value)
        <option
            value="{{ $associative ? $key : $value }}"
            {{ $oldValue == ($associative ? $key : $value) ? 'selected' : '' }}
        >
            {{ $value }}
        </option>
        @endforeach
    @endif
</select>

@include('components.form.error', [
    'name' => $name,
    'error' => $error ?? null,
])
