@foreach($inputs as $input)
    @php($type = $input['type'] ?? 'text')
    @php($colWidthLabel = $labelWidth ?? 4)
    @php($colWidthInput = $inputWidth ?? 12 - $colWidthLabel)

    <div class="form-group row {{ ($contained ?? true) && $loop->last ? 'mb-0' : '' }}">
        <label
            for="input{{ Str::studly($input['name']) }}"
            class="col-xl-{{ $colWidthLabel }} col-sm-{{ $colWidthLabel + 1 }} col-form-label"
        >{{ $input['label'] }}:</label>

        <div class="col-xl-{{ $colWidthInput }} col-sm-{{ $colWidthInput -1 }} {{ in_array($type, ['radio', 'checkbox']) ? 'col-form-label' : '' }}">
            @empty($input['view'])
                @includeFirst(["components.form.{$type}", 'components.form.input'], $input)
            @else
                {!! $input['view'] !!}
            @endempty

            @isset($input['help'])
                <small class="form-text text-muted">{!! $input['help'] !!}</small>
            @endisset
        </div>
    </div>
@endforeach
