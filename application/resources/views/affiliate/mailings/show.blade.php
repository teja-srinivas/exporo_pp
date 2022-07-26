<?php /** @var \App\Models\Mailing $mailing */ ?>

@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
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

        <h6>Text-Version</h6>
        <textarea class="form-control" rows="30" readonly>{{
            $mailing->getTextForUser(auth()->user())
        }}</textarea>

        @if( $mailing->html )
        <h6 class="mt-4">HTML-Vorschau</h6>
        <iframe id="mail-preview" class="w-100 border rounded bg-light"
                style="height: 700px" src="{{ route('affiliate.mails.preview', [ 'mail' => $mailing ]) }}"></iframe>

        <div class="mt-2">
          <button
            class="btn btn-primary btn-sm mr-2"
            onclick="iframeToClipboard('mail-preview')"
          >HTML in die Zwischenablage kopieren</button>
          <a href="{{ route('affiliate.mails.download', [ 'mail' => $mailing ]) }}" target="_blank" class="btn btn-light btn-sm">HTML-Datei herunterladen</a>
        </div>
        @endif
    @endcard

    @include('affiliate.mailings.partials.details')
@endsection
