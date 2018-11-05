@extends('layouts.sidebar')

@section('title', 'Provisionsschema')

@section('main-content')
    <div class="rounded bg-white">
        @include('components.bundle-editor', ['bonuses' => $bonuses, 'editable' => false])
    </div>
@endsection
