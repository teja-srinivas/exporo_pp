@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('users.index') => 'Benutzer',
        route('users.show', $contract->user) => $contract->user->getDisplayName(),
        $contract->getTitle(),
        $contract->getKey(),
        'Bearbeiten'
    ])
@endsection

@include('contracts.partials.actions', ['contract' => $contract])

@section('main-content')
    @include('contracts.partials.template-link', compact('contract'))
    @include("contracts.edit.{$contract->type}")
    @include('contracts.partials.details')
    @include('components.audit', ['model' => $contract])
@endsection
