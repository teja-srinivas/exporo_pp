@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        route('affiliate.links.index') => 'Links',
        $link->title,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('affiliate.links.update', $link) }}" method="POST">
        @method('PUT')
        @csrf

        @card
            @include('components.form.builder', [
                'labelWidth' => 3,
                'contained' => false,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                        'default' => $link->title,
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Beschreibung',
                        'name' => 'description',
                        'default' => $link->description,
                    ],
                    [
                        'type' => 'text',
                        'label' => 'URL',
                        'required' => true,
                        'name' => 'url',
                        'default' => $link->url,
                        'help' => $__env->make('components.tag-help')->render(),
                    ],
                ],
            ])

            @include('affiliate.links.partials.short-link-partners', compact('link', 'shortLinkPartners'))

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('affiliate.links.partials.details')

    @include('components.audit', ['model' => $link])
@endsection
