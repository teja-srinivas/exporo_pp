@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">{{ __('Register') }}</div>

        <div class="card-body">
            @include('auth.partials.register')
        </div>
    </div>
</div>
@endsection
