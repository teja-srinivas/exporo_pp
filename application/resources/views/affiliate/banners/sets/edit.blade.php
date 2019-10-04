@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        'Verwaltung',
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
                'labelWidth' => 1,
                'inputs' => [
                    [
                        'type' => 'text',
                        'label' => 'Titel',
                        'name' => 'title',
                        'required' => true,
                        'default' => $set->title,
                    ],
                    [
                        'label' => 'URLs',
                        'name' => 'urls',
                        'required' => true,
                        'view' => $__env->make('affiliate.banners.sets.partials.urls', ['set' => $set])->render(),
                        'help' => $__env->make('components.tag-help')->render(),
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
