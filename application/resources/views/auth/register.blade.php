@extends('layouts.app')

@section('content')
    <div class="container">
        @card
        <h3>Exporo-Partnerprogramm</h3>
        <h4>Werden Sie jetzt Partner beim Exporo Partnerprogramm und sichern Sie sich attraktive Provisionen!</h4>
        <hr>
        @include('auth.partials.register')
        @endcard
    </div>
@endsection
