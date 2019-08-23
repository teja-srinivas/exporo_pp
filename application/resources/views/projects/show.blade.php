@extends('layouts.sidebar')

@section('title')
    @breadcrumbs([
        route('projects.index') => 'Projekte',
        $project->description,
    ])
@endsection

@section('main-content')
    @unless($project->wasApproved())
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="border bg-white p-3 d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offenes Projekt:</strong>
                    Damit Provisionen berechnet und ausgezahlt werden können,
                    muss das Projekt bestätigt werden.
                </div>

                <button type="submit" name="accept" value="1" class="btn btn-sm btn-success mx-2">Bestätigen</button>
            </div>
        </form>
    @endif

    @card
        @slot('subtitle', 'Alle Angaben werden synchronisiert mit dem Hauptsystem.')

        @unless(empty($project->image))
            @slot('info')
                <img src="https://cdn.exporo.de/image-cache/400/{{ $project->image }}" class="img-fluid">
            @endslot
        @endif

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
                <td>Fundingstart</td>
                <td>{{ optional($project->launched_at)->format('d.m.Y') ?? '(keine Angabe)' }}</td>
            </tr>
            <tr>
                <td>Laufzeit</td>
                <td>{{ $project->runtimeInMonths() }} Monate</td>
            </tr>
            <tr>
                <td>Rückzahlung</td>
                <td>
                    Minimallaufzeit: <b>{{ optional($project->payback_min_at)->format('d.m.Y') ?? '(keine Angabe)' }}</b><br>
                    Maximallaufzeit: <b>{{ optional($project->payback_max_at)->format('d.m.Y') ?? '(keine Angabe)' }}</b>
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

                @include('components.form.select', [
                    'type' => 'select',
                    'label' => __('Schema'),
                    'name' => 'schema',
                    'required' => true,
                    'default' => $project->schema_id,
                    'values' => $schemas,
                    'assoc' => true,
                ])

                <button class="btn btn-primary ml-2">Schema Ändern</button>
            </form>
        @endslot
    @endcard

    @card
        @slot('title', 'Provisionstyp')
        @slot('subtitle')
            Aktueller Typ:

            <a href="{{ route('commissions.types.show', $project->commissionType) }}">
                {{ $project->commissionType->name }}
            </a>
        @endslot
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

            @include('components.form.select', [
                'type' => 'select',
                'label' => __('Typ'),
                'name' => 'commissionType',
                'required' => true,
                'default' => $project->commission_type,
                'values' => $commissionTypes,
                'assoc' => true,
            ])

            <button class="btn btn-primary ml-2">Typ Ändern</button>
        </form>
        @endslot
    @endcard

@endsection
