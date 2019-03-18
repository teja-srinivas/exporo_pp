@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('users.index') => 'Benutzer',
        $user->getDisplayName(),
    ])
@endsection

@section('actions')
    @can('delete', $user)
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-flex mr-2">
            @method('DELETE')
            @csrf
            <button class="btn btn-outline-danger btn-sm">Löschen</button>
        </form>
    @endcan

    @role('admin')
        <a href="{{ $user->getLoginLink() }}" class="btn btn-outline-primary btn-sm mr-2">Login</a>
    @endrole

    @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
    @endcan
@endsection

@section('main-content')
    @if($user->parent_id > 0)
        <h5>
            Unterpartner von:

            @if($user->parent)
                <a href="{{ route('users.show', $user->parent) }}">
                    {{ $user->parent->getDisplayName() }}
                </a>
            @else
                #{{ $user->parent_id }} (Benutzer existiert nicht)
            @endif
        </h5>
    @endif

    @include('users.partials.application')

    <div class="shadow-sm my-3 bg-white">
        <div class="p-2">
            <div class="row text-center text-nowrap">
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    <a href="{{ route('users.investors.index', $user) }}" class="btn btn-outline-light p-0 btn-block">
                        <span class="text-reset">Meine Kunden</span>
                        <div class="h2 mb-1">{{ $investors->count }}</div>
                    </a>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    <a href="{{ route('users.investments.index', $user) }}" class="btn btn-outline-light p-0 btn-block">
                        <span class="text-reset">Investments</span>
                        <div class="h2 mb-1">{{ $investors->investments }}</div>
                    </a>
                </div>
                <div class="col-sm border-sm-right mb-3 mb-sm-0">
                    <a href="{{ route('users.users.index', $user) }}" class="btn btn-outline-light p-0 btn-block">
                        <span class="text-reset">Subpartner</span>
                        <div class="h2 mb-1">{{ $user->children()->count() }}</div>
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

        @include('components.bundle-editor', ['bonuses' => $user->bonuses])

        @slot('footer')
            <form action="{{ route('users.update', $user) }}" method="POST"
                  class="d-flex justify-content-end form-inline">
                @method('PUT')
                @csrf

                <input type="hidden" name="redirect" value="back">

                @include('components.form.select', [
                    'type' => 'select',
                    'name' => 'bonusBundle',
                    'required' => true,
                    'values' => $bonusBundles,
                    'assoc' => true,
                    'groups' => true,
                ])

                <button class="btn btn-primary ml-2">Ersetzen</button>
            </form>
        @endslot
    @endcard

    @card
        @slot('title', 'Abrechnungen')
        @slot('info', 'der bereits erfolgten Provisionsansprüchen.')

        <table class="table table-sm table-hover table-striped mb-0 table-borderless text-nowrap">
            <thead>
            <tr>
                <th>Zeitraum</th>
                <th class="text-right">Provisionen</th>
                <th class="text-right">Summe</th>
                <th width="100">Datum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->bills as $bill)
                <tr>
                    <td>
                        <a href="{{ route('bills.show', $bill) }}">{{ $bill->getDisplayName() }}</a>
                    </td>
                    <td class="text-right">{{ $bill->commissions }}</td>
                    <td class="text-right">{{ format_money($bill->gross) }}</td>
                    <td>{{ optional($bill->created_at)->format('d.m.Y') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keine Provisionen abgerechnet</td>
                </tr>
            @endforelse
            </tbody>
            @unless($user->bills->isEmpty())
            <tfoot>
                <tr>
                    <td class="border-top text-right"><strong>Total</strong></td>
                    <td class="border-top text-right">{{ $user->bills->sum('commissions') }}</td>
                    <td class="border-top text-right">{{ format_money($user->bills->sum('gross')) }}</td>
                    <td class="border-top"></td>
                </tr>
            </tfoot>
            @endunless
        </table>

        @slot('footer')
            <div class="d-flex justify-content-between align-items-center">
                @if($user->canBeBilled())
                    <span class="text-muted">Erhält Abrechnungen</span>
                @else
                    <b class="text-danger">Blockiert durch Status</b>
                @endif

                <a href="{{ route('bills.preview', $user) }}" class="btn btn-sm btn-primary">
                    Vorschau der nächsten Abrechnung
                </a>
            </div>
        @endslot
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
                    <td>{{ optional($agb->pivot->created_at)->format('d.m.Y') }}</td>
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
                    <td>{{ optional($document->created_at)->format('d.m.Y') }}</td>
                </tr>
            @empty
                <tr class="text-center text-muted">
                    <td colspan="4">Noch keinen Dokumenten zugewiesen</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @can('create', \App\Models\Document::class)
            @slot('footer')
                <div class="text-right">
                    <a href="{{ route('documents.create', ['user_id' => $user]) }}"
                       class="btn btn-primary btn-sm">Dokument Hochladen</a>
                </div>
            @endslot
        @endcan
    @endcard

    @include('users.partials.details')
@endsection
