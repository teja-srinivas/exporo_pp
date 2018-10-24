<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="row">
        <div class="col-lg">
            <div class="form-group row">
                <label for="inputCompany" class="col-sm-4 col-form-label">{{ __('Company') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'company',
                        'autocomplete' => 'organization',
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputTitle" class="col-sm-4 col-form-label">{{ __('Title') }}</label>
                <div class="col-sm-8">
                    @include('components.form.select', [
                        'name' => 'title',
                        'values' => \App\Models\User::TITLES,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="salutation1" class="col-sm-4 col-form-label font-weight-bold">{{ __('Salutation') }}*</label>
                <div class="col-sm-8 pt-1">
                    <div class="custom-control custom-control-inline custom-radio{{ $errors->has('salutation') ? ' is-invalid' : '' }}">
                        <input type="radio" id="salutation1" name="salutation" value="female" class="custom-control-input"
                               {{ old('salutation') === 'female' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="salutation1">Frau</label>
                    </div>
                    <div class="custom-control custom-control-inline custom-radio{{ $errors->has('salutation') ? ' is-invalid' : '' }}">
                        <input type="radio" id="salutation2" name="salutation" value="male" class="custom-control-input"
                               {{ old('salutation') === 'male' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="salutation2">Herr</label>
                    </div>

                    @include('components.form.error', ['name' => 'salutation', 'class' => 'd-block'])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputFirstName" class="col-sm-4 col-form-label font-weight-bold">{{ __('First Name') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'first_name',
                        'autocomplete' => 'given-name',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputLastName" class="col-sm-4 col-form-label font-weight-bold">{{ __('Last Name') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'last_name',
                        'autocomplete' => 'family-name',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputBirthDay" class="col-sm-4 col-form-label font-weight-bold">{{ __('Birthday') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.birthday', [
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputBirthPlace" class="col-sm-4 col-form-label font-weight-bold">{{ __('Birthplace') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'birth_place',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAddressStreet" class="col-sm-4 col-form-label">{{ __('Address') }}</label>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-8">
                            @include('components.form.input', [
                                'name' => 'address_street',
                                'autocomplete' => 'address-line1',
                                'placeholder' => 'Musterstraße',
                                'error' => false,
                            ])
                        </div>
                        <div class="col-4">
                            @include('components.form.input', [
                                'name' => 'address_number',
                                'placeholder' => '12c',
                                'error' => false,
                            ])
                        </div>
                    </div>

                    @include('components.form.error', ['name' => 'address_street'])
                    @include('components.form.error', ['name' => 'address_number'])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAddressAddition" class="col-sm-4 col-form-label">{{ __('Address Addition') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'address_addition',
                        'autocomplete' => 'address-line2',
                        'placeholder' => 'Haus 1 (optional)',
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAddressZipcode" class="col-sm-4 col-form-label font-weight-bold">{{ __('ZIP Code') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'address_zipcode',
                        'autocomplete' => 'postal-code',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAddressCity" class="col-sm-4 col-form-label font-weight-bold">{{ __('City') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'address_city',
                        'autocomplete' => 'address-level2',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail" class="col-sm-4 col-form-label font-weight-bold">{{ __('E-Mail Address') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'email',
                        'name' => 'email',
                        'autocomplete' => 'email',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="inputPhone" class="col-sm-4 col-form-label font-weight-bold">{{ __('Telephone') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'tel',
                        'name' => 'phone',
                        'autocomplete' => 'tel-national',
                        'required' => true,
                    ])
                </div>
            </div>
        </div>

        <div class="col-lg">
            <div class="form-group row">
                <label for="inputWebsite" class="col-sm-4 col-form-label">{{ __('Your Website') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'url',
                        'name' => 'website',
                        'autocomplete' => 'url',
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputVatId" class="col-sm-4 col-form-label">{{ __('VAT ID') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'vat_id',
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputTaxOffice" class="col-sm-4 col-form-label">{{ __('Tax Office') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'tax_office',
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 col-form-label font-weight-bold">{{ __('Password') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'password',
                        'name' => 'password',
                        'autocomplete' => 'new-password',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPasswordConfirmation" class="col-sm-4 col-form-label font-weight-bold">{{ __('Confirm Password') }}*</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'password',
                        'name' => 'password_confirmation',
                        'autocomplete' => 'new-password',
                        'required' => true,
                        'error' => false,
                    ])
                </div>
            </div>

            <div class="form-group mt-4">
                <p>
                    Mit der Anmeldung zum Exporo Partnerprogramm schließen Sie einen Partnervertrag
                    mit der Exporo AG und der Exporo Investment GmbH.
                </p>

                <p>Sie erklären sich mit</p>

                @component('components.form.checkbox', ['name' => 'legal_exporo_ag'])
                    <span>den</span>
                    <a href="{!! $agbs[\App\Models\Agb::TYPE_AG] !!}">AGB</a>
                    &
                    <a href="#">Datenschutzerklärung</a>
                    der Exporo AG,
                @endcomponent

                @include('components.form.error', ['name' => 'llegal_exporo_ag'])

                @component('components.form.checkbox', [
                    'name' => 'legal_exporo_gmbh',
                    'class' => 'mt-2',
                    'required' => true,
                ])
                    <span>den</span>
                    <a href="{!! $agbs[\App\Models\Agb::TYPE_GMBH] !!}">AGB</a>
                    & Datenschutzerklärung der Exporo Investment GmbH,
                    sowie den Bestimmungen zu Cookies & Internet-Werbung einverstanden.
                @endcomponent

                @include('components.form.error', ['name' => 'legal_exporo_gmbh'])

                @component('components.form.checkbox', [
                    'name' => 'legal_transfer',
                    'class' => 'mt-4',
                    'required' => true,
                ])
                    <span>Sie</span> sind bin mit der Weitergabe Ihrer personenbezogenen Daten von der
                    Exporo Investment GmbH an die Exporo AG zum Zwecke der Verwaltung Ihrer
                    personenbezogenen Daten und der Abrechnung Ihrer Vergütung einverstanden.
                @endcomponent

                @include('components.form.error', ['name' => 'legal_transfer'])
            </div>

            <div class="form-group row">
                <button type="submit" class="btn btn-lg btn-success mx-auto">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center small">Pflichtfelder sind mit * gekennzeichnet und müssen ausgefüllt werden.</div>
</form>
