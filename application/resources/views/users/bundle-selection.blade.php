@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center my-4">
            <h1>Das Exporo Partnerprogramm</h1>
            <h3>
                Du bist nur noch einen Schritt davon entfernt, Dich als Exporo Partner zu registrieren!
            </h3>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('users.bundle-selection.store', auth()->user()) }}" method="POST">
                    @csrf

                    @card
                        <p>
                            An dieser Stelle möchten wir Dich bitten, eines unserer Provisionsmodelle zu wählen:
                        </p>

                        @foreach($bundles as $bundle)
                            @php($key = $bundle->getKey())
                            @php($id = 'bundleSelection_' . $key)

                            <div class="d-flex mb-3">
                                <div class="custom-control custom-radio mt-1">
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
                                    @include('components.bundle-editor', ['bonuses' => $bundle->bonuses])
                                </div>
                            </div>
                        @endforeach

                        <p>
                            Nachdem wir Deine Daten geprüft und Dein Provisionsmodel festgelegt haben,
                            erhälst Du eine E-Mail mit der Freigabe zu unserem Partnerprogramm.
                        </p>

                        Bei Fragen kannst Du Dich jederzeit an uns wenden &ndash;
                        schreibe einfach eine E-Mail an <a href="mailto:partner@exporo.com">partner@exporo.com</a>,
                        oder rufe uns unter <a href="tel:+4940210917370">040 210 91 73-70</a> an.

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
