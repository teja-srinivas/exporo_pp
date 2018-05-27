@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @card
                @slot('title', __('Login'))

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-4 col-form-label text-md-right">
                            {{ __('E-Mail Address') }}
                        </label>
                        <div class="col-md-6">
                            @include('components.form.input', [
                                'type' => 'email',
                                'name' => 'email',
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
                                'autocomplete' => 'current-password',
                                'required' => true,
                            ])
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            @include('components.form.checkbox', [
                                'name' => 'remember',
                                'label' => __('Remember Me'),
                            ])
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    </div>
                </form>
            @endcard
        </div>
    </div>
</div>
@endsection
