@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('agbs.index') => 'AGBs',
        $agb->name,
    ])
@endsection

@section('actions')
    @if($agb->canBeDeleted() && auth()->user()->can('delete', $agb))
        <form action="{{ route('agbs.destroy', $agb) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $agb)
        <a href="{{ route('agbs.edit', $agb) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @include('users.partials.table', ['users' => $users])
    @include('agbs.partials.details')
@endsection
