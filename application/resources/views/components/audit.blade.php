@can('view audits')
<div class="table-responsive shadow-sm mt-3">
    <table class="table table-sm small table-striped mb-0 bg-white">
        <thead>
        <tr>
            <th class="border-top-0">Name</th>
            <th class="border-top-0">Änderung</th>
            <th class="border-top-0">Von</th>
            <th class="border-top-0">In</th>
        </tr>
        </thead>
        <tbody>
        {{-- We collect all models and convert their audits into pairs --}}
        {{-- of model and audit to preserve their original relation --}}
        {{-- as it is not preserved in the audit model itself. --}}
        @php($models = is_a($model, Illuminate\Database\Eloquent\Model::class) ? [$model] : $model)
        @php($audits = collect($models)->map(function ($model) {
            return $model->audits->load('user')->map(function ($audit) use ($model) {
                return [$model, $audit];
            });
        })->flatten(1))

        {{-- We then display each entry ordered by creation date --}}
        @forelse($audits->sortByDesc(function ($entry) {
            [, $audit] = $entry;
            return $audit->created_at;
        }) as [$entity, $audit])
            @php($_modifications = $audit->getModified())

            <tr>
                <td width="130" class="border-right" rowspan="{{ count($_modifications) }}">
                    <strong>
                        @empty($audit->user)
                            <span class="text-muted">
                                @if($audit->user_id === null)
                                    System
                                @else
                                    Unbekannt (@json($audit->user))
                                @endif
                            </span>
                        @else
                        <a href="{{ route('users.show', $audit->user) }}">
                            {{ $audit->user->first_name }}
                            {{ $audit->user->last_name }}
                        </a>
                        @endempty
                    </strong>
                    <br>
                    @unless($audit->event === 'updated')
                        <strong>{{ $audit->event }}</strong>
                        <br>
                    @endunless
                    @timeago($audit->created_at)
                </td>
                @foreach($audit->getModified() as $title => $row)
                    @unless($loop->first)
                        </tr><tr>
                    @endif

                    <td>
                        <strong>{{ $title }}</strong>
                    </td>
                    <td>
                    @isset($row['old'])
                        @if(in_array($title, $entity->getHidden()))
                            <span class="text-muted">(versteckt)</span>
                        @elseif(is_bool($row['old']))
                            {!! $row['old'] ? '✔' : '-' !!}
                        @else
                            {{ $row['old'] }}
                        @endif
                    @endisset
                    </td>
                    <td>
                    @isset($row['new'])
                        @if(in_array($title, $entity->getHidden()))
                            <span class="text-muted">(versteckt)</span>
                        @elseif(is_bool($row['new']))
                            {!! $row['new'] ? '✔' : '-' !!}
                        @else
                            {{ $row['new'] }}
                        @endif
                    @endisset
                    </td>
                @endforeach
            </tr>
        @empty
            <tr class="disabled">
                <td colspan="4" class="text-center text-muted">Noch keine Änderungen vollzogen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endcan
