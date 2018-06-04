@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('users.index') => 'Benutzer',
        "{$user->first_name} {$user->last_name}",
    ])
@endsection

@section('actions')
    @can('delete', $user)
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-flex">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
        </form>
    @endcan

    @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @card
        @slot('title', 'AGBs')
        @slot('info', 'die von diesem Nutzer akzeptiert wurden.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
                <th width="140">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->agbs as $agb)
                <tr>
                    <td><a href="{{ route('agbs.show', $agb) }}">{{ $agb->name }}</a></td>
                    <td>{{ $agb->pivot->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine AGBs akzeptiert</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

    @card
        @slot('title', 'Dokumente')
        @slot('info', 'die bisher mit dem Nutzer geteilt wurden. Die Dateien werden als PDF gespeichert.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
                <th width="140">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->documents as $document)
                <tr>
                    <td>
                        <div>
                            <a href="{{ route('documents.show', $document) }}">{{ $document->name }}</a>
                        </div>

                        <small style="line-height: 1rem">{{ $document->description }}</small>
                    </td>
                    <td>{{ $document->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keinen Dokumenten zugewiesen</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @slot('footer')
            <div class="text-right">
                <a href="{{ route('documents.create', ['user_id' => $user]) }}"
                   class="btn btn-primary btn-sm">Dokument Hochladen</a>
            </div>
        @endslot
    @endcard

    @card
        @slot('title', 'Investoren')
        @slot('info', '')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->investors as $investor)
                <tr>
                    <td>{{ $investor->last_name }}, {{ $investor->first_name }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Investoren hinterlegt</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

    @include('users.details')
@endsection
