@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @card
                @slot('title', __('Set Password'))

                <form method="POST" action="{{ route('password.request') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="inputEmail" class="col-md-4 col-form-label text-md-right">
                            {{ __('E-Mail Address') }}
                        </label>
                        <div class="col-md-6">
                            @include('components.form.input', [
                                'type' => 'email',
                                'name' => 'email',
                                'default' => $email,
                                'autocomplete' => 'email',
                                'autofocus' => true,
                                'required' => true,
                            ])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword" class="col-md-4 col-form-label text-md-right">
                            {{ __('Password') }}
                        </label>
                        <div class="col-md-6">
                            @include('components.form.input', [
                                'type' => 'password',
                                'name' => 'password',
                                'autocomplete' => 'new-password',
                                'required' => true,
                            ])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPasswordConfirmation" class="col-md-4 col-form-label text-md-right">
                            {{ __('Confirm Password') }}
                        </label>
                        <div class="col-md-6">
                            @include('components.form.input', [
                                'type' => 'password',
                                'name' => 'password_confirmation',
                                'autocomplete' => 'new-password',
                                'required' => true,
                            ])
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Set Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            @endcard
        </div>
    </div>
</div>
@endsection
