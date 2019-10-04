@can('process', $contract)
<div class="py-2 px-3 bg-white rounded shadow-sm">
    Erstellt aus Vorlage:
    <strong>
        <a href="{{ route('contracts.templates.edit', $contract->template) }}">
            {{ $contract->template->name }}
        </a>
    </strong>
</div>
@endcan
