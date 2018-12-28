@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        route('affiliate.mails.index') => 'Mailings',
        $mailing->title,
    ])
@endsection

@section('actions')
    @if(auth()->user()->can('delete', $mailing))
        <form action="{{ route('affiliate.mails.destroy', $mailing) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endif

    @can('update', $mailing)
        <a href="{{ route('affiliate.mails.edit', $mailing) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @card
        @slot('title', $mailing->title)

        <p>{{ $mailing->description }}</p>

        <textarea class="form-control" rows="30" readonly>{{
            $mailing->getTextForUser(auth()->user())
        }}</textarea>
    @endcard

    @include('affiliate.mailings.partials.details')
@endsection
