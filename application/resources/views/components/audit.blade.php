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
        @forelse($model->audits->load('user')->sortByDesc('created_at') as $audit)
            @php($_modifications = $audit->getModified())

            <tr>
                <td width="130" class="border-right" rowspan="{{ count($_modifications) }}">
                    <strong class="mb-0">
                        <a href="{{ route('users.show', $audit->user) }}">
                            {{ $audit->user->first_name }}
                            {{ $audit->user->last_name }}
                        </a>
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
                        @if(in_array($title, $model->getHidden()))
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
                        @if(in_array($title, $model->getHidden()))
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
