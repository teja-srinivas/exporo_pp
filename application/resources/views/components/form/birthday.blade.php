<div class="text-nowrap">
    @include('components.form.select', [
        'name' => 'birth_day',
        'emptyText' =>  __('Day'),
        'autocomplete' => $autocomplete ?? 'bday-day',
        'required' => $required ?? false,
        'error' => false,
        'default' => optional($default ?? null)->day,
        'values' => range(1, 31),
    ])
    @include('components.form.select', [
        'name' => 'birth_month',
        'assoc' => true,
        'emptyText' => __('Month'),
        'autocomplete' => $autocomplete ?? 'bday-month',
        'required' => $required ?? false,
        'error' => false,
        'default' => optional($default ?? null)->month,
        'values' => collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => now()->setDate(2018, $month, 1)->monthName];
        }),
    ])
    @include('components.form.select', [
        'name' => 'birth_year',
        'emptyText' => __('Year'),
        'autocomplete' => $autocomplete ?? 'bday-year',
        'required' => $required ?? false,
        'error' => false,
        'default' => optional($default ?? null)->year,
        'values' => range(now()->year - 17, now()->year - 120),
    ])
</div>

@include('components.form.error', [
    'name' => 'birth_*',
    'class' => 'd-block'
])
