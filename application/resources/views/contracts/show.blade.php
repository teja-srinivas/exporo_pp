@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
    route('users.index') => 'Benutzer',
    route('users.show', $contract->user) => $contract->user->getDisplayName(),
    'Verträge',
    $contract->getKey(),
    ])
@endsection

@if($contract->isEditable())
@section('actions')
    @can('management.contracts.delete')
        <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
            @method('DELETE')
            @csrf

            <button type="submit" class="btn btn-outline-danger btn-sm">
                Entwurf löschen
            </button>
        </form>
    @endcan
@endsection
@endif

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
