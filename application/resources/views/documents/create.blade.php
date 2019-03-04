@extends('layouts.sidebar')

@section('title')
    @empty($user)
        @breadcrumps([
            route('documents.index') => 'Dokumente',
            'Neu Anlegen',
        ])
    @else
        @breadcrumps([
            route('users.index') => 'Benutzer',
            route('users.show', $user) => $user->details->display_name,
            'Dokument Anlegen'
        ])
    @endif
@endsection

@section('main-content')
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @card
            <div class="form-group row">
                <label for="inputUser" class="col-sm-3 col-form-label">Benutzer:</label>
                <div class="col-sm-9">
                    @include('components.form.select', [
                        'name' => 'user',
                        'values' => $users,
                        'assoc' => true,
                        'default' => optional($user)->getKey(),
                    ])
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Anzeigename:</label>
                <div class="col-sm-9">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'name',
                    ])
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFile" class="col-sm-3 col-form-label">PDF-Datei:</label>
                <div class="col-sm-9">
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
                <label for="inputDescription" class="col-sm-3 form-control-plaintext">Notiz:</label>
                <div class="col-sm-9">
                    <textarea name="description" id="inputDescription" class="form-control" cols="30" rows="5">{{ old('description') }}</textarea>
                    @include('components.form.error', ['name' => 'description'])
                </div>
            </div>

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Dokument Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
