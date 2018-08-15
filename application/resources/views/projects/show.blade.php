@extends('layouts.sidebar')

@section('title')
    @breadcrumps([
        route('projects.index') => 'Projekte',
        $project->name,
    ])
@endsection

@section('main-content')
    @unless($project->wasApproved())
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="border bg-white p-3 d-flex align-items-start">
                <div class="flex-fill">
                    <strong>Offenes Projekt:</strong>
                    Damit Provisionen berechnet und ausgezahlt werden können,
                    muss das Projekt bestätigt werden.
                </div>

                <button type="submit" name="accept" value="1" class="btn btn-sm btn-success mx-2">Bestätigen</button>
            </div>
        </form>
    @endif

    @unless(empty($project->image))
        <img src="{{ $project->image }}" class="img-fluid">
    @endif

    @unless(empty($project->description))
        @card
            @slot('title', 'Beschreibung')
            {{ $project->description }}
        @endcard
    @endif

    @card
        @slot('title', 'Details')
        @slot('subtitle', 'Alle Angaben werden synchronisiert mit dem Hauptsystem.')

        <table class="table table-borderless table-striped table-sm m-0">
            <tbody>
            <tr>
                <td>Zinssatz</td>
                <td>{{ $project->interest_rate }}%</td>
            </tr>
            <tr>
                <td>Marge</td>
                <td>{{ $project->margin }}%</td>
            </tr>
            <tr>
                <td>Gestartet</td>
                <td>@timeago($project->launched_at)</td>
            </tr>
            <tr>
                <td>Laufzeit</td>
                <td>{{ $project->runtimeInMonths() }} Monate</td>
            </tr>
            <tr>
                <td>Rückzahlung</td>
                <td>
                    vom {{ optional($project->payback_min_at)->format('d.m.Y') ?? '(keine Angabe)' }}<br>
                    bis {{ optional($project->payback_max_at)->format('d.m.Y') ?? '(keine Angabe)' }}
                </td>
            </tr>
            </tbody>
        </table>
    @endcard

    @card
        @slot('title', 'Abrechnungsschema')
        @slot('subtitle')
            Jetziges Schema:

            <a href="{{ route('schemas.show', $project->schema) }}">
                {{ $project->schema->name }}
            </a>
        @endslot

        <code class="d-block text-center lead">{{ $project->schema->formula }}</code>

        @slot('footer')
            {{--
                TODO: only show this form if we don't have any bills on this project yet
                Should in that case already calculated commissions count as well?
                Or only care about approved comissions? TBD.
            --}}
            <form action="{{ route('projects.update', $project) }}" method="POST"
                  class="d-flex justify-content-end form-inline">
                @method('PUT')
                @csrf

                @include('components.form.builder', [
                    'inputs' => [
                        [
                            'type' => 'select',
                            'label' => __('Schema'),
                            'name' => 'schema',
                            'required' => true,
                            'default' => $project->schema_id,
                            'values' => $schemas,
                            'assoc' => true,
                        ],
                    ],
                ])

                <button class="btn btn-primary">Schema Ändern</button>
            </form>
        @endslot
    @endcard
@endsection
