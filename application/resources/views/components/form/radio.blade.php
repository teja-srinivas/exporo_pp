@php($prefix = 'input' . studly_case($name))
@php($inlineClass = count($values) <= 3 ? 'custom-control-inline' : '')

@foreach($values as $key => $label)
    @php($id = $loop->first ? $prefix : $prefix . $loop->iteration)

    <div class="custom-control {{ $inlineClass }} custom-radio">
        <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $key }}" class="custom-control-input"
                {{ old($name, $default ?? null) == $key ? 'checked' : '' }}>
        <label class="custom-control-label" for="{{ $id }}">{{ $label }}</label>
    </div>
@endforeach

@include('components.form.error', [
    'name' => $name,
    'error' => $error ?? null,
    'class' => $errors->has($name) ? 'd-block' : ''
])
