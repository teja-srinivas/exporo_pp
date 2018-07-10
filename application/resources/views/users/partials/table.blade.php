<table class="bg-white shadow-sm accent-primary table table-borderless table-sm table-hover table-striped
table-sticky position-relative">
    <thead>
    <tr>
        <th>Nachname</th>
        <th>Vorname</th>
        <th class="text-right">Status</th>
        <th width="160">Datum</th>
    </tr>
    </thead>
    <tbody>
    @forelse($users as $user)
        <tr>
            <td><a href="{{ route('users.show', $user) }}">{{ $user->last_name }}</a></td>
            <td><a href="{{ route('users.show', $user) }}">{{ $user->first_name }}</a></td>
            <td class="text-right">
                @if($user->rejected())
                    <div class="badge badge-danger">Abgelehnt</div>
                @elseif($user->notYetAccepted())
                    <div class="badge badge-warning">Ausstehend</div>
                @endif

                @foreach ($user->roles as $role)
                    <a href="{{ route('roles.show', $role) }}"
                       class="badge badge-{{ App\User::getRoleColor($role) }}">
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