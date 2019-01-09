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
        Hier findest du verschiedene E-Mail-Vorlagen, die Sie verwenden und ergänzen kannst um auf Exporo
        und die aktuellen Immobilienprojekte aufmerksam zu machen. Sie können den Text einfach kopieren
        und über Ihr E-Mail Programm versenden.
    @endcard

    <table class="table bg-white table-borderless table-striped shadow-sm leading-sm">
        <tbody>
        @forelse($mailings as $mailing)
            <tr>
                <td>
                    <div class="lead font-weight-bold mb-1">
                        <a href="{{ route('affiliate.mails.show', $mailing) }}">{{ $mailing['title'] }}</a>
                    </div>
                    <span class="small text-muted">{{ $mailing['description'] }}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-muted text-center">Keine Mailings zur Auswahl</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
