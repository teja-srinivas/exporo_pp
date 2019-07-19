{{-- Validation Errors --}}
@if((isset($errors) && $errors->any()) || !empty(session('error-message')))
    <div class="alert alert-warning">
        {{ session('error-message') ?? 'Es sind Fehler beim Speichern aufgetreten' }}
    </div>
@endif

@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
