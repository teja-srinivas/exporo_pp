<?php /** @var App\User $user */ ?>

@extends('layouts.sidebar')

@section('title', $users->count() . ' Benutzer')

@section('actions')
    @can('create', App\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Neu Anlegen</a>
    @endcan
@endsection

@section('main-content')
    @include('users.partials.table')
@endsection
