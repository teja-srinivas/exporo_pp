@extends('layouts.app')

@section('content')
    <div class="container">
        @card
        <h3>Exporo-Partnerprogramm</h3>
        <h4>Werde jetzt Partner beim Exporo Partnerprogramm und sichere Dir attraktive Provisionen!</h4>
        <hr>
        @include('auth.partials.register')
        @endcard
    </div>
@endsection
