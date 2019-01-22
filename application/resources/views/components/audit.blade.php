@can('view audits')
    <h5 class="mt-4 mb-3">Änderungsprotokoll</h5>
    <div class="table-responsive shadow-sm">
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
            @php($models = collect(array_wrap($model)))
            @php($models->each->loadMissing('audits.auditable', 'audits.user'))
            @php($audits = $models->pluck('audits')->flatten(1)->filter(function($audit) {
                return !empty($audit->new_values) || !empty($audit->old_values);
            }))

            @forelse($audits->sortByDesc('event')->sortByDesc('created_at') as $audit)
                @php($_modifications = $audit->getModified())
                @php($entity = $audit->auditable)

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
                                    {{ $audit->user->getDisplayName() }}
                                </a>
                            @endempty
                        </strong>
                        <br>
                        @unless($audit->event === 'updated')
                            <strong>{{ $audit->event }}</strong>
                            <br>
                        @endunless
                        {{ $audit->created_at->format('d.m.Y') }}
                    </td>
                    @foreach($audit->getModified() as $title => $row)
                        @unless($loop->first)
                </tr>
                <tr>
                    @endif

                    <td>
                        <strong>{{ $title }}</strong>
                    </td>

                    @foreach(['old', 'new'] as $key)
                        <td>
                            @unless(array_key_exists($key, $row))
                                @continue
                            @endif

                            @if($row[$key] === null)
                                <code class="text-muted small">NULL</code>
                            @elseif($entity !== null && in_array($title, $entity->getHidden()))
                                <span class="text-muted">(versteckt)</span>
                            @elseif(is_bool($row[$key]))
                                {!! $row[$key] ? '✔' : '-' !!}
                            @elseif(is_array($row[$key]))
                                <pre class="m-0">{{ var_export($row[$key], true) }}</pre>
                            @else
                                @php($value = App\Traits\Encryptable::decrypt($row[$key]))

                                @if(starts_with($value, 'eyJpdiI6'))
                                    <span class="text-muted">(verschlüsselt)</span>
                                @else
                                    {{ $value }}
                                @endif
                            @endif
                        </td>
                    @endforeach
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
