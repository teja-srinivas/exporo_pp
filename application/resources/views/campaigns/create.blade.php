@extends('layouts.sidebar')

@section('title', 'Kampagnen')
    @breadcrumbs([
        route('campaigns.index') => 'Kapmpagnen',
        'Erstellen',
    ])
@endsection

@section('main-content')
    @php($vueData = [
        'api' => route('api.campaigns.index'),
    ])
    <vue v-cloak class="cloak-fade" data-is="campaigns" data-props='@json($vueData)' />
    @card
        <div class="lead text-center text-muted">
            Hier etwas Text...
        </div>
    @endcard
@endsection
