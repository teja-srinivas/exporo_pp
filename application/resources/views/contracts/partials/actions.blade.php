@section('actions')
    @if($contract->pdf_generated_at !== null)
        <a href="{{ $contract->getDownloadUrl() }}" class="btn btn-secondary btn-sm">.PDF</a>
    @endif

    @if($contract->isEditable())
        @can('delete', $contract)
            <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
                @method('DELETE')
                @csrf

                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Entwurf löschen
                </button>
            </form>
        @endcan

        @can('process', $contract)
            <form action="{{ route('contract-status.update', $contract) }}" method="POST" class="ml-3">
                @method('PUT')
                @csrf

                @if($contract->released_at === null)
                    <button type="submit" name="release" value="1" class="btn btn-success py-1 px-3">
                        Für Partner freigeben
                    </button>
                @else
                    <button type="submit" name="release" value="0" class="btn btn-danger py-1 px-3">
                        Freigabe zurücknehmen
                    </button>
                @endif
            </form>
        @endcan
    @endif
@endsection
