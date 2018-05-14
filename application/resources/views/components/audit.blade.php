@can('view audits')
<table class="table table-sm small table-borderless table-striped shadow-sm mb-0 bg-white mt-3">
    <thead>
    <tr class="border-bottom">
        <th>Name</th>
        <th>Änderungen</th>
    </tr>
    </thead>
    <tbody>
    @forelse($model->audits->load('user') as $audit)
        <tr>
            <td width="130">
                <h6 class="mb-0">
                    <a href="{{ route('users.show', $audit->user) }}">
                        {{ $audit->user->first_name }}
                        {{ $audit->user->last_name }}
                    </a>
                </h6>
                <abbr title="{{ $audit->created_at }}">
                    {{ $audit->created_at->diffForHumans() }}
                </abbr>
            </td>
            <td>
                @foreach($audit->getModified() as $title => $row)
                    <div class="row">
                        <div class="col-sm font-weight-bold">{{ $title }}</div>
                        <div class="col-sm-5">{{ $row['old'] }}</div>
                        <div class="col-sm-5">{{ $row['new'] }}</div>
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
@endcan
