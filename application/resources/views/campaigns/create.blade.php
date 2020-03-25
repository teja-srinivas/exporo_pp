@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
        route('campaigns.index') => 'Kampagnen',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    @php($vueData = [
        'api' => route('api.campaigns.store'),
        'action' => 'create',
        'redirect' => route('campaigns.index'),
        'users' => $users,
        'user' => Auth::user(),
    ])
    <vue v-cloak class="cloak-fade" data-is="campaign-editor" data-props='@json($vueData)' />
@endsection
