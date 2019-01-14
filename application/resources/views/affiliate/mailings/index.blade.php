@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        'Mailings',
    ])
@endsection

@section('actions')
    @can('create', \App\Models\Mailing::class)
        <a href="{{ route('affiliate.mails.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    @card
        Hier finden Sie verschiedene E-Mail-Vorlagen, die Sie verwenden und ergänzen können um auf Exporo
        auf die aktuellen Immobilienprojekte aufmerksam zu machen. Sie können den Text einfach kopieren
        und über Ihr E-Mail Programm versenden.
    @endcard

    @forelse($mailings as $mailing)
        <div class="p-3 rounded bg-white shadow-sm my-2">
            <div class="lead font-weight-bold mb-2 leading-sm">
                <a href="{{ route('affiliate.mails.show', $mailing) }}">{{ $mailing['title'] }}</a>
            </div>
            <span class="leading-sm text-muted">{{ $mailing['description'] }}</span>
        </div>
    @empty
        <div class="p-3 rounded bg-white shadow-sm my-2 text-muted text-center">
            Keine Mailings zur Auswahl
        </div>
    @endforelse
@endsection
