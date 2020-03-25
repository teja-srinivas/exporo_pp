@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
        route('campaigns.index') => 'Kampagnen',
        $campaign->title,
    ])
@endsection

@section('actions')
    @can('delete', $campaign)
        <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="d-inline-flex mr-2">
            @method('DELETE')
            @csrf
            @include('components.dialog', ['confirmLabel' => 'Löschen', 'message' => 'Soll die Kampagne wirklich gelöscht werden?'])
        </form>
        <button class="btn btn-outline-danger btn-sm mr-2" onclick="showDialog()">Löschen</button>
    @endcan
@endsection

@section('main-content')
    @php($vueData = [
        'api' => [
            'put' => route('api.campaigns.update', $campaign),
            'delete_file' => route('api.campaigns.file.delete'),
        ],
        'action' => 'edit',
        'redirect' => route('campaigns.index'),
        'editedCampaign' => $campaign,
        'users' => $users,
        'user' => Auth::user(),
    ])
    <vue v-cloak class="cloak-fade" data-is="campaign-editor" data-props='@json($vueData)' />
@endsection
