@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        'Links',
    ])
@endsection

@section('main-content')
    @foreach([
        'Startseite' => 'https://exporo.de/?a_aid=',
        'Registrierungs-Landingpage' => 'https://p.exporo.de/registrierung/?a_aid=',
    ] as $title => $prefix)
        @card
            @slot('title', $title)
            <input type="text" readonly class="form-control form-control-lg" value="{{ $prefix . auth()->user()->id }}">
        @endcard
    @endforeach
@endsection
