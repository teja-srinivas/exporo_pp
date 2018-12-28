<table class="bg-white shadow-sm accent-primary table table-borderless table-sm table-hover table-striped
table-sticky position-relative">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Firma</th>
        <th class="text-right">Status</th>
        <th width="160">Datum</th>
    </tr>
    </thead>
    <tbody>
    @forelse($users as $user)
        <tr>
            @php($showUserLink = route('users.show', $user))
            <td><a href="{{ $showUserLink }}" class="text-muted">#{{ $user->id }}</a></td>
            <td><a href="{{ $showUserLink }}">{{ $user->last_name . ', ' . $user->first_name}}</a></td>
            <td><a href="{{ $showUserLink }}">{{ $user->details->company }}</a></td>
            <td class="text-right">
                @if($user->rejected())
                    <div class="badge badge-danger">Abgelehnt</div>
                @elseif($user->notYetAccepted())
                    <div class="badge badge-warning">Ausstehend</div>
                @endif

                @foreach ($user->roles as $role)
                    <a href="{{ route('roles.show', $role) }}"
                       class="badge badge-{{ \App\Models\User::getRoleColor($role) }}">
                        {{ studly_case($role->name) }}
                    </a>
                @endforeach
            </td>
            <td>{{ $user->created_at->format('d.m.Y H:i:s') }}</td>
        </tr>
    @empty
        <tr class="text-center text-muted">
            <td colspan="4">Noch keine Benuzer angelegt</td>
        </tr>
    @endforelse
    </tbody>
    </tbody>
</table>
