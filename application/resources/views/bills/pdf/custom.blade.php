<h4>Sonderbuchungen</h4>

<table class="table table-sm table-bordered border-left-0 border-right-0 mb-5 table-foot-totals bg-white">
    <thead>
    <tr>
        <th class="border-top-0">Titel</th>
        <th class="border-top-0">Datum</th>
        <th class="border-top-0">Provision</th>
    </tr>
    </thead>

    <tbody>
    @foreach($custom as $commission)
        <tr>
            <td>{{ $commission['note_public'] }}</td>
            <td class="text-right text-nowrap">{{ $commission['created_at']->format('d.m.Y') }}</td>
            <td class="text-right text-nowrap">{{ format_money((float) $commission['net']) }}</td>
        </tr>
    @endforeach

    <tr>
        <td class="text-right text-nowrap font-weight-bold" colspan="2">Provision Total</td>
        <td class="text-right text-nowrap font-weight-bold">{{ format_money((float) $customNetSum) }}</td>
    </tr>
    </tbody>
</table>
