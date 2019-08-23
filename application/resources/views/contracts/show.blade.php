@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
    route('users.index') => 'Benutzer',
    route('users.show', $contract->user) => $contract->user->getDisplayName(),
    'Verträge',
    $contract->getKey(),
    ])
@endsection

@section('main-content')
    @card
        @include('contracts.partials.header', ['user' => $contract->user])
    @endcard

    <div class="rounded bg-white shadow-sm p-3">
        @include('components.bundle-editor', [
            'bonuses' => $contract->bonuses,
            'editable' => false,
            'legacy' => true,
        ])
    </div>

    @include('contracts.partials.details')
@endsection
