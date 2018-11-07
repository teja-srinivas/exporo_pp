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
            'female' => 'female',
            'male' => 'male',
        ],
    ],
    [
        'type' => 'select',
        'name' => 'title',
        'label' => __('Title'),
        'values' => \App\Models\User::TITLES,
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
        @include('components.form.birthday', [
            'default' => $user->details->birth_date,
        ])
    </div>
</div>

@include('components.form.builder', ['inputs' => $decorate([
    [
        'name' => 'birth_place',
        'label' => __('Birthplace'),
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
    ],
    [
        'name' => 'address_city',
        'label' => __('City'),
        'autocomplete' => 'address-level2',
    ],
    [
        'type' => 'tel',
        'name' => 'phone',
        'label' => __('Telephone'),
        'autocomplete' => 'tel-national',
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
    [
        'type' => 'text',
        'name' => 'iban',
        'label' => __('IBAN'),
    ],
])])
