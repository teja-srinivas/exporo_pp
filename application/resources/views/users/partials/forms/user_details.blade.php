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
    <label for="inputBirthDay" class="col-xl-4 col-sm-5 col-form-label">{{ __('Birthday') }}</label>
    <div class="col-xl-8 col-sm-7">
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
    <label for="inputAddressStreet" class="col-xl-4 col-sm-5 col-form-label">{{ __('Address') }}</label>
    <div class="col-xl-8 col-sm-7">
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

        @include('components.form.error', ['name' => 'address_street', 'class' => 'd-block'])
        @include('components.form.error', ['name' => 'address_number', 'class' => 'd-block'])
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
        'type' => 'text',
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
        'class' => 'text-monospace',
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
        'class' => 'text-monospace',
    ],
    [
        'type' => 'text',
        'name' => 'bic',
        'label' => __('BIC'),
        'class' => 'text-monospace',
    ],
])])

@can('manage', $user)
    <h6 class="mt-4 pt-2 mb-2 text-uppercase tracking-wide">Mehrwertsteuer</h6>

    @include('components.form.builder', ['inputs' => $decorate([
        [
            'type' => 'number',
            'name' => 'vat_amount',
            'label' => 'Betrag in Prozent',
        ],
        [
            'type' => 'radio',
            'name' => 'vat_included',
            'label' => 'Berechnung',
            'values' => [
                false => 'On Top',
                true => 'Inkludiert',
            ],
        ],
    ])])
@endcan
