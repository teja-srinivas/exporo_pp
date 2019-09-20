@if($user->hasNotBeenProcessed())
    @if($user->canBeAccepted())
        <div class="alert alert-light">
            <div class="d-flex align-items-baseline">
                <div class="flex-fill">
                    <strong>Offene Anfrage:</strong>
                    Diese Benutzer-Anfrage wurde noch nicht bestätigt.
                </div>

                <div class="mx-2">
                    @empty($user->draftContract)
                        (Ohne Vertrag)
                    @else
                        @php($editable = $user->draftContract->isEditable() && $user->can('update', $user->draftContract))

                        <a href="{{ route($editable ? 'contracts.edit' : 'contracts.show', $user->draftContract) }}" class="btn btn-sm btn-success">
                            Annehmen
                        </a>
                    @endempty
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
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
            <strong>Offene Anfrage:</strong>
            Benutzer kann erst akzeptiert werden, sobald ein Vertrag akzeptiert wurde.
        </div>
    @endif
@endif
