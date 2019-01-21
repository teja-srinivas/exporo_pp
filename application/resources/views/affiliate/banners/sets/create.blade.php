@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        route('banners.sets.index') => 'Banner',
        'Neu Anlegen',
    ])
@endsection

@section('main-content')
    <form action="{{ route('banners.sets.store') }}" method="POST">
        @csrf

        @card
            @include('components.form.builder', [
                'labelWidth' => 1,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                    ],
                    [
                        'type' => 'urls',
                        'label' => 'URLs',
                        'name' => 'urls',
                        'required' => true,
                        'help' => 'Folgende Textbausteine stehen zur verfügung:<br><code>#reflink</code> für "?a_aid=&lt;benutzerid&gt;"'
                    ],
                ],
            ])

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Neu Anlegen</button>
                </div>
            @endslot
        @endcard
    </form>
@endsection
