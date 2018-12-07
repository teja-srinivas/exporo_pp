@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('bills.index') => 'Rechnungen',
        route('users.show', $user) => $user->getDisplayName(),
        $bill->getDisplayName(),
    ])
@endsection

@section('main-content')
    @includeWhen($investments->isNotEmpty(), 'bills.pdf.projects')
    @includeWhen($investors->isNotEmpty(), 'bills.pdf.registrations')
    @includeWhen($overheads->isNotEmpty(), 'bills.pdf.overhead')
    @includeWhen($custom->isNotEmpty(), 'bills.pdf.custom')
@endsection
