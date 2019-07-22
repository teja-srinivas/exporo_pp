@if($user->hasNotBeenProcessed())
    @if($user->canBeAccepted())
        <div class="alert alert-light">
            <div class="d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offene Anfrage:</strong>
                    Diese Benutzer-Anfrage wurde noch nicht bestätigt.
                </div>

                <a href="{{ route('contracts.edit', $user->draftContract) }}" class="btn btn-sm btn-success mx-2">
                    Annehmen
                </a>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <strong>Offene Anfrage:</strong>
            Benutzer kann erst akzeptiert werden, sobald ein Vertrag akzeptiert wurde.
        </div>
    @endif
@endif

@unless($user->hasVerifiedEmail())
    <div class="alert alert-warning">
        <strong>Offene Validierung:</strong>
        Der Nutzer hat die angegebene Email noch nicht verifiziert.
    </div>
@endunless
