@if($user->hasNotBeenProcessed())
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="border bg-white p-3 d-flex align-items-center">
            <div class="flex-fill">
                <strong>Offene Anfrage:</strong>
                Diese Benutzer-Anfrage wurde noch nicht bestätigt.
            </div>

            <button type="submit" name="accept" value="1" class="btn btn-sm btn-success mx-2">Annehmen</button>
            <button type="submit" name="accept" value="0" class="btn btn-sm btn-danger">Ablehnen</button>
        </div>
    </form>
@endif
