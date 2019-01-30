@if($user->hasNotBeenProcessed())
    <div class="border bg-white p-3 my-3">
        @if($user->canBeAccepted())
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
        @else
            <strong>Offene Anfrage:</strong>
            Benutzer kann erst akzeptiert werden, sobald ein Schema ausgewählt ist.
        @endif
    </div>
@endif

@unless($user->hasVerifiedEmail())
    <div class="border bg-white p-3 d-flex align-items-center">
        <div class="flex-fill">
            <strong>Offene Validierung:</strong>
            Der Nutzer hat die angegebene Email noch nicht verifiziert.
        </div>
    </div>
@endunless
