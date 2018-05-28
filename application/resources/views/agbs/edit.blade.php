@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('agbs.index') => 'AGBs',
        route('agbs.show', $agb) => $agb->name,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('agbs.update', $agb) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @card
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label font-weight-bold">Anzeigename:</label>
                <div class="col-sm-7">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'name',
                        'default' => $agb->name,
                    ])
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFile" class="col-sm-3 col-form-label font-weight-bold">PDF-Datei:</label>
                <div class="col-sm-7">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input{{ $errors->has('file') ? ' is-invalid' : '' }}"
                               id="inputFile" name="file">
                        <label class="custom-file-label" for="customFile">Datei auswählen (.PDF)</label>
                    </div>

                    @if ($errors->has('file'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <a href="{!! $agb->getDownloadUrl() !!}"
                       class="btn btn-secondary btn-block btn-sm {{ empty($agb->filename) ? 'disabled' : '' }}">
                        PDF Anzeigen
                    </a>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-sm-3 form-control-plaintext font-weight-bold">Anzeige:</div>
                <div class="col-sm-9">
                    <div class="custom-control custom-checkbox form-control-plaintext">
                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                               name="is_default" {{ old('is_default', $agb->is_default) ? 'checked' :'' }}>
                        <label class="custom-control-label" for="customCheck1">
                            Wird neuen Registrierungen, sowie bisherigen Nutzern, zur Akzeptierung angezeigt
                        </label>
                    </div>
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('agbs.details')

    @include('components.audit', ['model' => $agb])
@endsection
