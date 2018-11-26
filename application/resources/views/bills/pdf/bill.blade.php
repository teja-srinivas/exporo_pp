@extends('bills.pdf.layout')

@section('cover')
    @include('bills.pdf.cover')
@endsection

@section('content')
    @includeWhen($investments->isNotEmpty(), 'bills.pdf.projects')
    @includeWhen($investors->isNotEmpty(), 'bills.pdf.registrations')
    @includeWhen($overheads->isNotEmpty(), 'bills.pdf.overhead')
    @includeWhen($custom->isNotEmpty(), 'bills.pdf.custom')
@endsection
