@extends('bills.pdf.layout')

@section('cover')
    @include('bills.pdf.cover')
@endsection

@unless($investments->isEmpty())
@section('projects')
    @include('bills.pdf.projects')
@endsection
@endunless

@unless($investors->isEmpty())
@section('registrations')
    <h3>Registrierungen</h3>
    @include('bills.pdf.registrations')
@endsection
@endunless

@unless($overheads->isEmpty())
@section('overheads')
    @include('bills.pdf.overhead')
@endsection
@endunless

@section('footer')
    @include('bills.pdf.footer')
@endsection
