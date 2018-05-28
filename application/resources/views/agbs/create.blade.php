@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('agbs.index') => 'AGBs',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('agbs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @card
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label font-weight-bold">Anzeigename:</label>
                <div class="col-sm-7">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'name',
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
            </div>
            <div class="form-group row mb-0">
                <div class="col-sm-3 form-control-plaintext font-weight-bold">Anzeige:</div>
                <div class="col-sm-9 pt-1">
                    @include('components.form.checkbox', [
                        'name' => 'is_default',
                        'label' => 'Wird neuen Registrierungen, sowie bisherigen Nutzern, zur Akzeptierung angezeigt',
                    ])
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Neu Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
