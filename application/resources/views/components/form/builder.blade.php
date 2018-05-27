@foreach($inputs as $input)
    @php($type = $input['type'] ?? 'text')

    <div class="form-group row {{ $loop->last ? 'mb-0' : '' }}">
        <label
            for="input{{ studly_case($input['name']) }}"
            class="col-sm-3 col-form-label"
        >{{ $input['label'] }}:</label>

        <div class="col-sm-9 {{ $type === 'radio' ? 'pt-1' : '' }}">
            @includeFirst(["components.form.{$type}", 'components.form.input'], $input)
        </div>
    </div>
@endforeach
