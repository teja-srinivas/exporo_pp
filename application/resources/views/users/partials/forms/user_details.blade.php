{{-- Helper to quickly map our inputs to the existing values --}}
@php($decorate = function ($array) use ($user) {
    foreach ($array as &$entry) {
        $entry['default'] = $user->details[$entry['name']];
    }

    return $array;
})

@include('components.form.builder', ['inputs' => $decorate([
    [
        'type' => 'radio',
        'name' => 'salutation',
        'label' => __('Salutation'),
        'values' => [
            'female' => 'Frau',
            'male' => 'Herr',
        ],
    ],
    [
        'type' => 'select',
        'name' => 'title',
        'label' => __('Title'),
        'values' => \App\User::TITLES,
    ],
    [
        'name' => 'company',
        'label' => __('Company'),
        'autocomplete' => 'organization',
    ],
])])

<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Herkunft</h6>

<div class="form-group row">
    <label for="inputBirthDay" class="col-sm-4 col-form-label">{{ __('Birthday') }}</label>
    <div class="col-sm-8">
        <div>
            @include('components.form.select', [
                'name' => 'birth_day',
                'emptyText' => '(' . __('Day') . ')',
                'autocomplete' => 'bday-day',
                'error' => false,
                'values' => range(1, 31),
            ])
            @include('components.form.select', [
                'name' => 'birth_month',
                'assoc' => true,
                'emptyText' => '(' . __('Month') . ')',
                'autocomplete' => 'bday-month',
                'error' => false,
                'values' => collect(range(1, 12))->mapWithKeys(function ($month) {
                    return [$month => now()->setDate(2018, $month, 1)->format('F')];
                }),
            ])
            @include('components.form.select', [
                'name' => 'birth_year',
                'emptyText' => '(' . __('Year') . ')',
                'autocomplete' => 'bday-year',
                'error' => false,
                'values' => range(now()->year - 17, now()->year - 120),
            ])
        </div>

        @include('components.form.error', [
            'name' => 'birth_*',
            'class' => 'd-block'
        ])
    </div>
</div>

@include('components.form.builder', ['inputs' => $decorate([
    [
        'name' => 'birth_place',
        'label' => __('Birthplace'),
        'required' => true,
    ],
])])

<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Kommunikation</h6>

<div class="form-group row">
    <label for="inputAddressStreet" class="col-sm-4 col-form-label">{{ __('Address') }}</label>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-8">
                @include('components.form.input', [
                    'name' => 'address_street',
                    'autocomplete' => 'address-line1',
                    'placeholder' => 'Musterstraße',
                    'default' => $user->details->address_street,
                    'error' => false,
                ])
            </div>
            <div class="col-4">
                @include('components.form.input', [
                    'name' => 'address_number',
                    'placeholder' => '12c',
                    'default' => $user->details->address_number,
                    'error' => false,
                ])
            </div>
        </div>

        @include('components.form.error', ['name' => 'address_street'])
        @include('components.form.error', ['name' => 'address_number'])
    </div>
</div>

@include('components.form.builder', ['inputs' => $decorate([
    [
        'name' => 'address_addition',
        'label' => __('Address Addition'),
        'autocomplete' => 'address-line2',
    ],
    [
        'name' => 'address_zipcode',
        'label' => __('ZIP Code'),
        'autocomplete' => 'postal-code',
        'required' => true,
    ],
    [
        'type' => 'tel',
        'name' => 'phone',
        'label' => __('Telephone'),
        'autocomplete' => 'tel-national',
        'required' => true,
    ],
    [
        'type' => 'url',
        'name' => 'website',
        'label' => __('Website'),
        'autocomplete' => 'url',
    ],
])])

<h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Finanzen</h6>

@include('components.form.builder', ['inputs' => $decorate([
    [
        'type' => 'text',
        'name' => 'vat_id',
        'label' => __('VAT ID'),
    ],
    [
        'type' => 'text',
        'name' => 'tax_office',
        'label' => __('Tax Office'),
    ],
])])
