@can('view audits')
<div class="table-responsive-md shadow-sm mt-3">
<table class="table table-sm small table-striped mb-0 bg-white">
    <thead>
    <tr class="border-bottom">
        <th class="border-top-0">Name</th>
        <th class="border-top-0">Änderungen</th>
    </tr>
    </thead>
    <tbody>
    @forelse($model->audits->load('user')->sortByDesc('created_at') as $audit)
        <tr>
            <td width="130">
                <strong class="mb-0">
                    <a href="{{ route('users.show', $audit->user) }}">
                        {{ $audit->user->first_name }}
                        {{ $audit->user->last_name }}
                    </a>
                </strong>
                <br>
                <abbr title="{{ $audit->created_at }}">
                    {{ $audit->created_at->diffForHumans() }}
                </abbr>
            </td>
            <td>
                @foreach($audit->getModified() as $title => $row)
                    <div class="row">
                        <div class="col-sm-4 col-lg-2 font-weight-bold">{{ $title }}</div>
                        <div class="col-sm-4 col-lg-5">
                            @if(is_bool($row['old']))
                                {!! $row['old'] ? '✔' : '-' !!}
                            @else
                                {{ $row['old'] }}
                            @endif
                        </div>
                        <div class="col-sm-4 col-lg-5">
                            @if(is_bool($row['new']))
                                {!! $row['new'] ? '✔' : '-' !!}
                            @else
                                {{ $row['new'] }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </td>
        </tr>
    @empty
        <tr class="disabled">
            <td colspan="2" class="text-center text-muted">Noch keine Änderungen vollzogen</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>
@endcan
