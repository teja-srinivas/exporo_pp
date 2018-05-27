<div class="custom-control custom-checkbox {{ $class ?? '' }}">
    @php($id = 'input' . studly_case($name))
    <input
        type="checkbox"
        class="custom-control-input"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ old($name, $default ?? null) ? 'checked' : '' }}
        @if($required ?? false) required @endif
    >
    <label class="custom-control-label" for="{{ $id }}">
        {{ $label ?? $slot ?? '' }}
    </label>
</div>
