@extends('layouts.app')

@section('content')
    <div class="container">
        @card
            @slot('title', 'Benutzerinformationen')

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('auth.partials.register')
        @endcard
    </div>
@endsection
