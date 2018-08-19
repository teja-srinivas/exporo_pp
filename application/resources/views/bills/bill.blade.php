@extends('bills.layout.billlayout')

@section('cover')
    @include('bills.cover')
@endsection

@section('projects')
    <h1 class="text-center mt-5"> Übersicht Provisionsgutschrift</h1>
    <h3 class="mt-5 text-center">Eigenumsatz</h3>

    @include('bills.projects')
@endsection
