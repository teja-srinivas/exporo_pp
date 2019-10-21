<a class="list-group-item list-group-item-action"
   href="{{ route($contract->isEditable() && auth()->user()->can('update', $contract) ? 'contracts.edit' : 'contracts.show', $contract) }}">
    <div class="d-flex w-100 justify-content-between align-items-start">
        <b>
            {{ $contract->created_at->format('d.m.Y') }}
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

            @if($contract->rejected_at !== null)
                <span class="badge badge-danger badge-pill">Abgewiesen</span>
            @elseif($contract->accepted_at === null)
                <span class="badge badge-warning badge-pill">Ausstehend</span>
            @else
                <span class="badge badge-success badge-pill">
                    Angenommen: <b>{{ $contract->accepted_at->format('d.m.Y') }}</b>
                </span>
            @endif
        </div>
    </div>

    <div class="small font-weight-bold">
        {{ $slot }}
    </div>
</a>
