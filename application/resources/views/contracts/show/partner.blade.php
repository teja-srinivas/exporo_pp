@card
    @slot('title', $contract->getTitle())
    <table class="table table-sm mb-0 table-fixed">
        <tr>
            <td>{{ __('Anspruch Kundenbindung') }}</td>
            <td>{{ trans_choice('time.years', $contract->claim_years) }}</td>
        </tr>
        <tr>
            <td>{{ __('Kündigungsfrist') }}</td>
            <td>{{ trans_choice('time.days', $contract->cancellation_days) }}</td>
        </tr>
        <tr>
            <td>@lang('Exklusiv für Exporo tätig')</td>
            <td>{{ $contract->is_exclusive ? 'Ja' : 'Nein' }}</td>
        </tr>
        <tr>
            <td>@lang('Erlaubt Subpartner')</td>
            <td>{{ $contract->allow_overhead ? 'Ja' : 'Nein' }}</td>
        </tr>
    </table>

    @unless(empty($contract->special_agreement))
        <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Sondervereinbarung</h6>
        <p>{{ $contract->special_agreement }}</p>
    @endunless
@endcard
