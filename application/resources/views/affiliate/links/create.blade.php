@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Werbemittel',
        route('affiliate.links.index') => 'Links',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('affiliate.links.store') }}" method="POST">
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
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Beschreibung',
                        'name' => 'description',
                    ],
                    [
                        'type' => 'text',
                        'label' => 'URL',
                        'required' => true,
                        'name' => 'url',
                        'help' => 'Folgende Textbausteine stehen zur verfügung:<br><code>#reflink</code> für "?a_aid=&lt;benutzerid&gt;"'
                    ],
                ],
            ])

            @include('affiliate.links.partials.short-link-partners', compact('shortLinkPartners'))

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Neu Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
