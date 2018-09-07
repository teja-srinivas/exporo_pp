@extends('bills.layout.billlayout')

@section('cover')
    @include('bills.cover')

    <h1 class="text-center mt-5"> Übersicht Provisionsgutschrift</h1>
@endsection

@unless($investments->isEmpty())
@section('projects')
    <h3 class="mt-5 text-center">Eigenumsatz</h3>
    @include('bills.projects')
@endsection
@endunless

@unless($investors->isEmpty())
@section('registrations')
    <h3 class="mt-5 text-center">Registrierungen</h3>
    @include('bills.registrations')
@endsection
@endunless

@section('footer')
    @include('bills.footer')
@endsection
