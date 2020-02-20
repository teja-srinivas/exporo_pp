@extends('layouts.sidebar')

@section('title', 'Übersicht')

@section('main-content')
    @if($investmentCount > 0)
        @php($vueData = [
            'apiInvestments' => route('api.dashboard.investments'),
            'apiCommissions' => route('api.dashboard.commissions'),
        ])
        <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
    @else
        @card
            <div class="lead text-muted">
                Das Partner Cockpit ist die zentrale Anlaufstelle für Ihre Tätigkeit
                als Partner. Sie können hier alle Aktivitäten Ihrer Kunden und Interessenten
                sehen und erhalten Statistiken zum Verhalten Ihrer Kunden. Zudem stehen
                Ihnen hier die monatliche Abrechnungen und Provisionsansprüche zur Verfügung.
                Das Partner Cockpit bieten Ihnen auch einen direkten Zugang zu Werbemitteln
                und Tools für eine nahtlose digitale Integration.
            </div>
            <div>
              <h4 class="mt-3 mb-3">Sehen Sie auf einen Blick die Entwicklung Ihrer Provisionen</h4>
                <img
                    src="{{ url('/images/investment_placeholder.png') }}"
                    alt="Provisionsbeispiel"
                    style="width: 100%;"
                >
            </div>
        @endcard
    @endif
@endsection
