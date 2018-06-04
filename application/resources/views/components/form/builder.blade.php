@foreach($inputs as $input)
    @php($type = $input['type'] ?? 'text')
    @php($colWidthLabel = $labelWidth ?? 4)
    @php($colWidthInput = $inputWidth ?? 12 - $colWidthLabel)

    <div class="form-group row {{ $loop->last ? 'mb-0' : '' }}">
        <label
            for="input{{ studly_case($input['name']) }}"
            class="col-sm-{{ $colWidthLabel }} col-form-label"
        >{{ $input['label'] }}:</label>

        <div class="col-sm-{{ $colWidthInput }} {{ $type === 'radio' ? 'pt-1' : '' }}">
            @includeFirst(["components.form.{$type}", 'components.form.input'], $input)
        </div>
    </div>
@endforeach
