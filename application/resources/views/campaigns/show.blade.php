@extends('layouts.sidebar')

@section('title', $campaign->title)

@section('main-content')
    @card
    <div class="lead text-muted">
        {{ $campaign->description }}
    </div>
    @endcard
@endsection

<style>
    
</style>
