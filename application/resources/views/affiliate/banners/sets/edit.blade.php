@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        'Werbemittel',
        route('banners.sets.index') => 'Banner',
        route('banners.sets.show', $set) => $set->title,
        'Bearbeiten',
    ])
@endsection

@section('main-content')
    <form action="{{ route('banners.sets.update', $set) }}" method="POST">
        @method('PUT')
        @csrf

        @card
            @include('components.form.builder', [
                'labelWidth' => 3,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                        'default' => $set->title,
                    ],
                    [
                        'type' => 'text',
                        'label' => 'URL',
                        'name' => 'urls[]',
                        'required' => true,
                        'default' => $set->urls,
                        'help' => 'Folgende Textbausteine stehen zur verfügung:<br><code>#reflink</code> für "?a_aid=&lt;benutzerid&gt;"'
                    ],
                ],
            ])

            @slot('footer')
                <div class="text-right">
                    <button class="btn btn-primary">Änderungen speichern</button>
                </div>
            @endslot
        @endcard
    </form>

    @include('affiliate.banners.sets.partials.details')

    @include('components.audit', ['model' => $set])
@endsection
