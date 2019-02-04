@if($user->hasNotBeenProcessed())
    @if($user->canBeAccepted())
        <div class="alert alert-light">
            <form action="{{ route('users.update', $user) }}" method="POST" class="d-flex align-items-baseline">
                @csrf
                @method('PUT')

                <div class="flex-fill">
                    <strong>Offene Anfrage:</strong>
                    Diese Benutzer-Anfrage wurde noch nicht bestätigt.
                </div>

                <button type="submit" name="accept" value="1" class="btn btn-sm btn-success mx-2">Annehmen</button>
                <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
            </form>
        </div>
    @else
        <div class="alert alert-warning">
            <strong>Offene Anfrage:</strong>
            Benutzer kann erst akzeptiert werden, sobald ein Schema ausgewählt ist.
        </div>
    @endif
@endif

@unless($user->hasVerifiedEmail())
    <div class="alert alert-warning">
        <strong>Offene Validierung:</strong>
        Der Nutzer hat die angegebene Email noch nicht verifiziert.
    </div>
@endunless
