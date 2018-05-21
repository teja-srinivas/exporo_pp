@if(($error ?? true) !== false && $errors->has($name))
    <span class="invalid-feedback {{ $class ?? '' }}">
        <strong>{{ $errors->first($name) }}</strong>
    </span>
@endif
