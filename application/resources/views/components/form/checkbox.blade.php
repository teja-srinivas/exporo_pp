<div class="custom-control custom-{{ $design ?? 'checkbox' }} {{ $class ?? '' }}">
    @php($name = $name ?? uniqid('input'))
    @php($id = 'input' . Str::studly($name))
    <input
        type="checkbox"
        class="custom-control-input"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ old($name, $default ?? null) ? 'checked' : '' }}
        @if($required ?? false) required @endif
        @if($disabled ?? false) disabled @endif
    >
    <label class="custom-control-label" for="{{ $id }}">
        {{ $description ?? $label ?? $slot ?? '' }}
    </label>
</div>

@include('components.form.error', [
    'name' => $name,
    'error' => $error ?? null,
    'class' => $errors->has($name) ? 'd-block' : '',
])
