<?php

$calculationTypes = [
    'first_investment' => 'Erstinvestment',
    'further_investment' => 'Folgeinvestment',
    'registration' => 'Registrierung',
];

?>

<table class="table table-borderless table-hover table-sm table-striped table-fixed mb-0">
    <colgroup>
        <col width="40%">
        <col width="40%">
        <col width="20%">
    </colgroup>
    <thead>
    <tr>
        <th>Typ</th>
        <th>Für</th>
        <th class="text-right">Wert</th>
    </tr>
    </thead>
    @if(count($bonuses) > 0)
        <tbody>
            @foreach($bonuses as $index => $bonus)
                <tr>
                    <td>
                      <strong>{{ $bonus->type->name }}</strong>
                    </td>
                    <td>
                        {{ $calculationTypes[$bonus->calculation_type] }}
                        @if($bonus->is_overhead)
                            <div
                                class="badge badge-info"
                            >
                                Overhead
                            </div>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $bonus->getDisplayValue(true) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    @endif
</table>