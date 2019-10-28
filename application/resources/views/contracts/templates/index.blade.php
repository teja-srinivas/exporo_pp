@extends('layouts.sidebar')

@section('title', $templates->count() . ' Vertragsvorlagen')

@section('actions')
    @can('create', \App\Models\ContractTemplate::class)
        <form action="{{ route('contracts.templates.store') }}" method="POST">
            @csrf
            @include('components.form.select', [
                'type' => 'select',
                'name' => 'type',
                'required' => true,
                'values' => $templateTypes,
                'assoc' => true,
                'class' => 'w-auto custom-select-sm',
            ])
            <button type="submit" class="btn btn-primary btn-sm ml-1">
                Vorlage Erstellen
            </button>
        </form>
    @endcan
@endsection

@section('main-content')
    <table class="bg-white shadow-sm accent-primary table table-borderless table-hover table-striped table-sticky table-sm">
        <thead>
        <tr>
            <th>Typ</th>
            <th>Name</th>
            <th width="80">Erstellt</th>
        </tr>
        </thead>
        <tbody>
        @forelse($templates as $template)
            <tr>
                <td>{{ __("contracts.{$template->type}.title") }}</td>
                <td>
                    <div class="d-flex justify-content-between">
                        @can('update', $template)
                            <a href="{{ route('contracts.templates.edit', $template) }}">
                                {{ $template->name }}
                            </a>
                        @else
                            {{ $template->name }}
                        @endcan

                        @if($template->is_default)
                            <div class="align-self-center badge badge-primary">Standard</div>
                        @endif
                    </div>
                </td>
                <td>{{ optional($template->created_at)->format('d.m.Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-muted text-center">
                    Noch keine Vorlagen erstellt
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
