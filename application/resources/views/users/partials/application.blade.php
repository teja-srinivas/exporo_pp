@if($user->isDeleted())
    <div class="alert alert-warning">
        <div class="d-flex align-items-baseline">
            <div class="flex-fill">
                Dieser Benutzer wurde gelöscht
            </div>
            @can('delete', $user)
                <form action="{{ route('users.restore', $user) }}" method="POST" class="d-inline-flex mr-2">
                    @csrf
                    <button class="btn btn-sm btn-danger">Wiederherstellen</button>
                </form>
            @endcan
        </div>
    </div>
@elseif($user->hasNotBeenProcessed())
    @if($user->canBeAccepted())
        <div class="alert alert-light">
            <div class="d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offene Anfrage:</strong>
                    Diese Benutzer-Anfrage wurde noch nicht bestätigt.
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="submit" name="accept" value="1"  class="btn btn-sm btn-success mx-2">
                        Annehmen
                    </button>

                    <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">
                        Ablehnen
                    </button>
                </form>
            </div>
        </div>
    @elseif(!$user->hasVerifiedEmail())
        <div class="alert alert-warning">
            <div class="d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offene Validierung:</strong>
                    Der Nutzer hat die angegebene Email noch nicht verifiziert.
                </div>

                <form action="{{ route('users.verification.store', $user) }}" method="POST">
                    @csrf
                    <button type="submit" name="accept" value="0" class="mx-2 btn btn-sm btn-primary">
                        DOI-Mail neu senden
                    </button>
                </form>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <div class="d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offene Anfrage:</strong>
                    Benutzer kann erst akzeptiert werden, sobald alle nötigen Verträge freigegeben wurden.
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
                </form>
            </div>
        </div>
    @endif
@elseif($user->rejected())
    <strong class="text-danger">
        Nutzer wurde am {{ $user->rejected_at->format('d.m.Y') }} abgelehnt.
    </strong>
@endif
