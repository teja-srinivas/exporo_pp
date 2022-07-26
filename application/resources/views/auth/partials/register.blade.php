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
                <label for="salutation1" class="col-sm-4 col-form-label font-weight-bold">{{ __('Salutation') }}
                    *</label>
                <div class="col-sm-8 pt-1">
                    <div class="custom-control custom-control-inline custom-radio{{ $errors->has('salutation') ? ' is-invalid' : '' }}">
                        <input type="radio" id="salutation1" name="salutation" value="female"
                               class="custom-control-input"
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
                <label for="inputFirstName" class="col-sm-4 col-form-label font-weight-bold">{{ __('First Name') }}
                    *</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'first_name',
                        'autocomplete' => 'given-name',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputLastName" class="col-sm-4 col-form-label font-weight-bold">{{ __('Last Name') }}
                    *</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'name' => 'last_name',
                        'autocomplete' => 'family-name',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputBirthDay" class="col-sm-4 col-form-label font-weight-bold">{{ __('Birthday') }}
                    *</label>
                <div class="col-sm-8">
                    @include('components.form.birthday', [
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAddressStreet" class="col-sm-4 col-form-label font-weight-bold">{{ __('Address') }}
                    *</label>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-8">
                            @include('components.form.input', [
                                'name' => 'address_street',
                                'autocomplete' => 'address-line1',
                                'placeholder' => 'Musterstraße',
                                'required' => true,
                            ])
                        </div>
                        <div class="col-4">
                            @include('components.form.input', [
                                'name' => 'address_number',
                                'placeholder' => '12c',
                                'required' => true,
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
                <label for="inputAddressZipcode" class="col-sm-4 col-form-label font-weight-bold">{{ __('ZIP Code') }}
                    *</label>
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
                <label for="inputEmail" class="col-sm-4 col-form-label font-weight-bold">{{ __('E-Mail Address') }}
                    *</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'email',
                        'name' => 'email',
                        'autocomplete' => 'email',
                        'required' => true,
                    ])
                </div>
            </div>

            <div class="form-group row">
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
            <div class="form-group row">
                <label for="inputWebsite" class="col-sm-4 col-form-label">{{ __('Your Website') }}</label>
                <div class="col-sm-8">
                    @include('components.form.input', [
                        'type' => 'text',
                        'name' => 'website',
                        'autocomplete' => 'url',
                    ])
                </div>
            </div>
        </div>

        <div class="col-lg">

            <div class="form-group">
                <p>
                    Mit der Anmeldung zum Exporo Partnerprogramm schließen Sie
                    einen Partnervertrag mit der Exporo AG.
                </p>

                <p>Hiermit willige ich ein,</p>

                @component('components.form.checkbox', [
                    'name' => 'legal_exporo_gmbh',
                    'class' => 'mt-4',
                    'required' => true,
                ])
                    Informationen über Kapitalanlagen, Projekt-Updates sowie plattform&shy;relevante
                    Informationen per Newsletter zu erhalten. Diese freiwillige
                    <a href="https://exporo.de/einwilligungserklaerung-exporo-investment-gmbh" target="_blank">Einwilligung</a>
                    kann ich jederzeit widerrufen. Zusätzlich stimme ich
                    <span>den</span>
                    <a href="{!! $agbs[\App\Models\Agb::TYPE_AG] !!}">AGB</a>
                    und der
                    <a href="https://exporo.de/datenschutz" target="_blank">Datenschutzerklärung</a>
                    der Exporo AG zu.
                @endcomponent

                @include('components.form.error', ['name' => 'legal_exporo_gmbh'])

                @component('components.form.checkbox', [
                    'name' => 'legal_transfer',
                    'class' => 'mt-4',
                    'required' => true,
                ])
                    <span>Sie</span> sind bin mit der Weitergabe Ihrer personenbezogenen Daten von der
                    EPH Investment GmbH an die Exporo AG zum Zwecke der Verwaltung Ihrer
                    personenbezogenen Daten und der Abrechnung Ihrer Vergütung einverstanden.
                @endcomponent

                @include('components.form.error', ['name' => 'legal_transfer'])

                @component('components.form.checkbox', [
                    'name' => 'cookie_advertisement',
                    'class' => 'mt-4',
                    'required' => true,
                ])
                    Mit den Bestimmungen zu <a href="https://exporo.de/datenschutz" target="_blank"> Cookies &
                        Internet-Werbung</a> erkläre ich mich einverstanden.
                @endcomponent
            </div>
        </div>
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
