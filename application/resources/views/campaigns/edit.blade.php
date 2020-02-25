@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
        route('campaigns.index') => 'Kampagnen',
        $campaign->title,
    ])
@endsection

@section('main-content')
    @php($vueData = [
        'api' => [
            'put' => route('api.campaigns.update', $campaign),
            'delete' => route('api.campaigns.destroy', $campaign),
            'delete_file' => route('api.campaigns.file.delete'),
        ],
        'action' => 'edit',
        'redirect' => route('campaigns.index'),
        'editedCampaign' => $campaign,
    ])
    <vue v-cloak class="cloak-fade" data-is="campaign-editor" data-props='@json($vueData)' />
@endsection
