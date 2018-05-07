@extends('layouts.sidebar')

@section('title')
    <a href="{{ route('agbs.index') }}" class="text-muted">AGBs</a>
    <span class="text-muted">/</span>
    <a href="{{ route('agbs.show', $agb) }}" class="text-muted">{{ $agb->name }}</a>
    <span class="text-muted">/</span>
    Bearbeiten
@endsection

@section('main-content')
    <form action="{{ route('agbs.update', $agb) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @card
            @slot('title', 'Angaben ändern')
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label font-weight-bold">Anzeigename:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           id="inputName" value="{{ old('name', $agb->name) }}" name="name">

                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFile" class="col-sm-2 col-form-label font-weight-bold">PDF-Datei:</label>
                <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input{{ $errors->has('file') ? ' is-invalid' : '' }}"
                               id="customFile" name="file">
                        <label class="custom-file-label" for="customFile">Datei auswählen (.PDF)</label>
                    </div>

                    @if ($errors->has('file'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <a href="{{ route('agbs.download', $agb) }}" class="btn btn-secondary btn-block btn-sm">
                        PDF Anzeigen
                    </a>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-sm-2 form-control-plaintext font-weight-bold">Anzeige:</div>
                <div class="col-sm-10">
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

@endsection