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
            @include('components.form.builder', [
                'contained' => false,
                'labelWidth' => 2,
                'inputWidth' => 8,
                'inputs' => [
                    [
                        'type' => 'select',
                        'label' => 'Kategorie',
                        'name' => 'type',
                        'required' => true,
                        'assoc' => true,
                        'default' => App\Agb::TYPE_AG,
                        'values' => collect(App\Agb::TYPES)->mapWithKeys(function (string $type) {
                            return [$type => __("agbs.type.{$type}")];
                        }),
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Anzeigename',
                        'name' => 'name',
                        'required' => true,
                    ],
                ]
            ])
            <div class="form-group row">
                <label for="inputFile" class="col-sm-2 col-form-label">PDF-Datei:</label>
                <div class="col-sm-8">
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
                <div class="col-sm-2 form-control-plaintext">Anzeige:</div>
                <div class="col-sm-10 pt-1">
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
