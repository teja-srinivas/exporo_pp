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
        <div class="lead text-center text-muted">
            Das Partner Cockpit ist die zentrale Anlaufstelle für Ihre Tätigkeit als Partner. Sie können hier alle Aktivitäten Ihrer Kunden und Interessenten sehen und erhalten Statistiken zum Verhalten Ihrer Kunden. Zudem stehen Ihnen hier die monatliche Abrechnungen und Provisionsansprüche zur Verfügung. Das Partner Cockpit bieten Ihnen auch auch einen direkten Zugang zu Werbemitteln und Tools für eine nahtlose digitale Integration.
        </div>
        @endcard
    @endif
@endsection
