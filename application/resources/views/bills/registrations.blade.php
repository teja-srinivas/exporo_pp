<div class="bg-white my-3 p-2 shadow-sm border-0 print-break-avoid">
    <table class="table table-striped table-sm mb-0 table-foot-totals">
        <thead>
        <tr>
            <th class="border-top-0">Anleger</th>
            <th class="border-top-0 text-right">Datum der Registrierung</th>
            <th class="border-top-0 text-right">Provision Netto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($investors->sortNatural('lastName') as $investor)
            <tr>
                <td>{{  $investor['last_name'] }}, {{ $investor['first_name'] }}</td>
                <td class="text-right">{{ $investor['created_at'] }}</td>
                <td class="text-right">{{ format_money($investor['net']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<table class="mt-3 table table-borderless lead table-sm">
    <tbody>
    <tr>
        <td class="text-right font-weight-bold">Netto Provision</td>
        <td class="text-right" width="150">{{ format_money($investorsNetSum) }}</td>
    </tr>
    </tbody>
</table>

