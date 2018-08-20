@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('bills.index') => 'Rechnungen',
        route('users.show', $user) => $user->getDisplayName(),
        $bill->getDisplayName(),
    ])
@endsection

@section('main-content')
    @include('bills.projects')
    @include('bills.registrations')
    @include('bills.footer')
@endsection
