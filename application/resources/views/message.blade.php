@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @card
                    @slot('title', $title ?? 'Hinweis')
                    {{ $message }}
                @endcard
            </div>
        </div>
    </div>
@endsection
