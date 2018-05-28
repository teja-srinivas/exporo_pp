@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('documents.index') => 'Dokumente',
        $document->name,
    ])
@endsection

@section('actions')
    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline-flex">
        @method('DELETE')
        @csrf
        <button class="btn btn-outline-danger btn-sm mr-2">Löschen</button>
    </form>

    <a href="{{ route('documents.edit', $document) }}" class="btn btn-primary btn-sm">Bearbeiten</a>
@endsection

@section('main-content')
    @unless(empty($document->description))
        <p class="lead">{{ $document->description }}</p>
    @endempty

    <embed width="100%" height="1080px" src="{!! $document->getDownloadUrl() !!}" type="application/pdf"
           class="bg-dark shadow-sm">

    @include('documents.details')
@endsection
