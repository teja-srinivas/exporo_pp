@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('documents.index') => 'Dokumente',
        route('documents.show', $document) => $document->name,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @card
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Anzeigename:</label>
                <div class="col-sm-7">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'name',
                        'default' => $document->name,
                    ])
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFile" class="col-sm-3 col-form-label">PDF-Datei:</label>
                <div class="col-sm-7">
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
                    <a href="{{ $document->getDownloadUrl() }}"
                       class="btn btn-secondary btn-block btn-sm {{ empty($document->filename) ? 'disabled' : '' }}">
                        PDF Anzeigen
                    </a>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label for="inputDescription" class="col-sm-3 form-control-plaintext">Notiz:</label>
                <div class="col-sm-9">
                    <textarea name="description" id="inputDescription" class="form-control"
                              cols="30" rows="5">{{ old('description', $document->description) }}</textarea>

                    @include('components.form.error', ['name' => 'description'])
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('documents.partials.details')

    @include('components.audit', ['model' => $document])
@endsection
