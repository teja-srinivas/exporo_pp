@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center my-4">
            <h1>Das Exporo Partnerprogramm</h1>
            <h3>
                Sie sind nur noch einen Schritt davon entfernt, sich als Exporo Partner zu registrieren!
            </h3>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('users.bundle-selection.store', auth()->user()) }}" method="POST">
                    @csrf

                    @card
                        <p>
                            An dieser Stelle möchten wir Sie bitten, eines unserer Provisionsmodelle zu wählen:
                        </p>

                        @foreach($bundles as $bundle)
                            @php($key = $bundle->getKey())
                            @php($id = 'bundleSelection_' . $key)

                            <div class="d-flex mb-4">
                                <div class="custom-control custom-radio">
                                    <input
                                        type="radio"
                                        id="{{ $id }}"
                                        name="bundle"
                                        value="{{ $key }}"
                                        class="custom-control-input"
                                        {{ old('bundle') === $key ? 'checked' : '' }}
                                    >

                                    <label class="custom-control-label" for="{{ $id }}">&nbsp;</label>
                                </div>

                                <div class="flex-fill shadow-sm rounded">
                                    <h5 class="text-dark">{{ $bundle->name }}</h5>
                                    @include('components.bundle-editor', ['bonuses' => $bundle->bonuses])
                                </div>
                            </div>
                        @endforeach

                        <p>
                            Nachdem Sie Ihr Provisionsmodel ausgewählt und wir Ihre Daten geprüft haben,
                            erhalten Sie eine E-Mail mit der Freigabe zu unserem Partnerprogramm.
                        </p>

                        Bei Fragen können Sie sich jederzeit an uns wenden &ndash;
                        schreiben Sie einfach eine E-Mail an <a href="mailto:partner@exporo.com">partner@exporo.com</a>,
                        oder rufen Sie uns unter <a href="tel:+4940210917370">040 210 91 73-70</a> an.

                        @slot('footer')
                            <div class="text-center">
                                <button class="btn btn-success">
                                    Auswählen
                                </button>
                            </div>
                        @endslot
                    @endcard
                </form>
            </div>
        </div>
    </div>
@endsection
