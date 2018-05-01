@extends('layouts.app')

@section('content')
<div class="container">
    @card
        @slot('title',  __('Register'))
        @include('auth.partials.register')
    @endcard
</div>
@endsection
