@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('users.index') => 'Benutzer',
        $user->getDisplayName(),
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
    @include('users.partials.application')

    <div class="shadow-sm my-3 bg-white">
        <div class="p-2">
            <div class="row text-center">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    <a href="{{ route('users.investors.index', $user) }}" class="btn btn-outline-light p-0 btn-block">
                        <span class="text-reset">Investoren</span>
                        <div class="h2 mb-1">{{ $investors->count }}</div>
                    </a>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    <a href="{{ route('users.investments.index', $user) }}" class="btn btn-outline-light p-0 btn-block">
                        <span class="text-reset">Investments</span>
                        <div class="h2 mb-1">{{ $investors->investments }}</div>
                    </a>
                </div>
                <div class="col-sm">
                    Investmentvolumen
                    <div class="h2 mb-1">{{ format_money($investors->amount) }}</div>
                </div>
            </div>
        </div>
    </div>

    @card
        @slot('title', 'Vergütungssschemata')
        @slot('info', 'für die jeweiligen Produkttypen')

        <table class="table table-borderless table-hover table-sm table-striped mb-0">
            <thead>
                <tr>
                    <th>Typ</th>
                    <th colspan="2">Für</th>
                    <th>Wert</th>
                </tr>
            </thead>
            @forelse($bonuses as $group)
            @foreach($group as $bonus)
                <tr>
                @if($loop->first)
                    <td
                        class="border-top bg-white"
                        rowspan="{{ $group->count() }}"
                    >
                        <a href="{{ route('commissionTypes.show', $group->first()->type) }}">
                            <strong>{{ $group->first()->type->name }}</strong>
                        </a>
                    </td>
                @endif
                    <td class="{{ $loop->first ? 'border-top' : '' }}">
                        {{ $bonus->getDisplayName() }}
                    </td>
                    <td class="{{ $loop->first ? 'border-top' : '' }}">
                        @if($bonus->is_overhead)
                            <div class="badge badge-info">Overhead</div>
                        @endif
                    </td>
                    <td class="text-right {{ $loop->first ? 'border-top' : '' }}">
                        {{ $bonus->getDisplayValue() }}
                    </td>
                </tr>
            @endforeach
            @empty
                <tr>
                    <td colspan="4" class="disabled text-muted text-center">
                        Kein Vergütungsschema hinterlegt
                    </td>
                </tr>
            @endforelse
        </table>
    @endcard

    @card
        @slot('title', 'Abrechnungen')
        @slot('info', 'der bereits erfolgten Provisionsansprüchen.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless">
            <thead>
            <tr>
                <th>Zeitraum</th>
                <th class="text-right">Provisionen</th>
                <th class="text-right">Summe</th>
                <th width="140">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->bills as $bill)
                <tr>
                    <td>
                        <div>
                            <a href="{{ route('bills.show', $bill) }}">{{ $bill->getDisplayName() }}</a>
                        </div>
                    </td>
                    <td class="text-right">{{ $bill->commissions }}</td>
                    <td class="text-right">{{ format_money($bill->net) }}</td>
                    <td>@timeago($bill->created_at)</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Provisionen abgerechnet</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcard

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
                    <td>@timeago($agb->pivot->created_at)</td>
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
                    <td>@timeago($document->created_at)</td>
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

    @include('users.partials.details')
@endsection
