<a class="list-group-item list-group-item-action"
   href="{{ route($contract->isEditable() && auth()->user()->can('update', $contract) ? 'contracts.edit' : 'contracts.show', $contract) }}">
    <div class="d-flex w-100 justify-content-between align-items-start">
        <b>
            {{ $contract->created_at->format('d.m.Y') }}
            <span class="text-muted">&mdash;</span>
            @lang("contracts.{$contract->type}.title")
        </b>

        <div class="d-flex align-items-baseline">
            @if($contract->isActive())
                <span class="badge badge-primary badge-pill mr-1">
                    Aktiv
                </span>
            @endif

            @unless(empty($contract->special_agreement))
                <div class="small badge badge-pill badge-secondary mr-1">
                    Sondervereinbarung
                </div>
            @endif

            @if($contract->terminated_at !== null)
                <span class="badge badge-light badge-pill mr-1">
                    Beendet: <b>{{ $contract->terminated_at->format('d.m.Y') }}</b>
                </span>
            @endif

            @if($contract->accepted_at !== null)
                <span class="badge badge-success badge-pill">
                    Angenommen: <b>{{ $contract->accepted_at->format('d.m.Y') }}</b>
                </span>
            @elseif($contract->released_at !== null)
                <span class="badge badge-warning badge-pill">
                    Freigegeben <b>{{ $contract->released_at->format('d.m.Y') }}</b>
                </span>
            @elseif($contract->accepted_at === null)
                <span class="badge badge-secondary badge-pill">Entwurf</span>
            @endif
        </div>
    </div>

    <div class="small font-weight-bold text-muted">
        {{ $slot }}
    </div>
</a>
