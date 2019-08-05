@foreach($inputs as $input)
    @php($type = $input['type'] ?? 'text')
    @php($colWidthLabel = $labelWidth ?? 4)
    @php($colWidthInput = $inputWidth ?? 12 - $colWidthLabel)

    <div class="form-group row {{ ($contained ?? true) && $loop->last ? 'mb-0' : '' }}">
        <label
            for="input{{ Str::studly($input['name']) }}"
            class="col-sm-{{ $colWidthLabel }} col-form-label"
        >{{ $input['label'] }}:</label>

        <div class="col-sm-{{ $colWidthInput }} {{ in_array($type, ['radio', 'checkbox']) ? 'col-form-label' : '' }}">
            @includeFirst(["components.form.{$type}", 'components.form.input'], $input)

            @isset($input['help'])
                <small class="form-text text-muted">{!! $input['help'] !!}</small>
            @endisset
        </div>
    </div>
@endforeach
