@extends('layouts.sidebar')

@section('title', $campaign->title)

@section('actions')
    @if($campaign->started_at !== null && $campaign->ended_at !== null)
        von: {{ $campaign->started_at }} bis: {{ $campaign->ended_at }}
    @endif
@endsection

@section('main-content')
    @card
    <div class="lead text-muted">
        {{ $campaign->description }}
    </div>
    @endcard
@endsection

<style>
    
</style>
