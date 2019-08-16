<div class="form-group row mt-2 mb-0">
    <label for="inputFile" class="col-sm-3 col-form-label">HTML-Datei:</label>
    <div class="col-sm-7">
        <div class="custom-file">
            <input type="file" class="custom-file-input{{ $errors->has('file') ? ' is-invalid' : '' }}"
                   id="customFile" name="file">
            <label class="custom-file-label" for="customFile">Datei auswählen (.html)</label>
        </div>

        @if ($errors->has('file'))
            <span class="invalid-feedback d-block">
                <strong>{{ $errors->first('file') }}</strong>
            </span>
        @endif
    </div>
</div>
