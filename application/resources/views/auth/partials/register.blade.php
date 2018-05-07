<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="row">
        <div class="col-lg">
            <div class="form-group row">
                <label for="company" class="col-sm-4 col-form-label">{{ __('Company') }}</label>

                <div class="col-sm-8">
                    <input id="company" type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company"
                           value="{{ old('company') }}" autocomplete="organization">

                    @if ($errors->has('company'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('company') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">{{ __('Title') }}</label>

                <div class="col-sm-8">
                    <select class="custom-select{{ $errors->has('title') ? ' is-invalid' : '' }} w-auto"
                            id="title" name="title" value="{{ old('title') }}">
                        <option value="" selected>--</option>
                        @foreach(\App\User::TITLES as $title)
                            <option>{{ $title }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
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

                    @if ($errors->has('salutation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('salutation') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="first_name" class="col-sm-4 col-form-label font-weight-bold">{{ __('First Name') }}*</label>

                <div class="col-sm-8">
                    <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name"
                           value="{{ old('first_name') }}" autocomplete="given-name" required>

                    @if ($errors->has('first_name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="last_name" class="col-sm-4 col-form-label font-weight-bold">{{ __('Last Name') }}*</label>

                <div class="col-sm-8">
                    <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name"
                           value="{{ old('last_name') }}" autocomplete="family-name" required>

                    @if ($errors->has('last_name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="birth_day" class="col-sm-4 col-form-label font-weight-bold">{{ __('Birthday') }}*</label>

                <div class="col-sm-8">
                    <div>
                        <select class="custom-select w-auto" id="birthDay" name="birth_day"
                                value="{{ old('birth_day') }}" autocomplete="bday-day" required>
                            <option {{ old('birth_day') ? '' : 'selected' }} disabled hidden>({{ __('Day') }})</option>
                            @foreach(range(1, 31) as $day)
                                <option value="{{ $day }}">{{ $day }}.</option>
                            @endforeach
                        </select>
                        <select class="custom-select w-auto ml-1" id="birthMonth" name="birth_month"
                                value="{{ old('birth_month') }}" autocomplete="bday-month" required style="flex-basis: 33%">
                            <option {{ old('birth_month') ? '' : 'selected' }} disabled hidden>({{ __('Month') }})</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">{{ \Date::now()->setDate(2018, $month, 1)->format('F') }}</option>
                            @endforeach
                        </select>
                        <select class="custom-select w-auto ml-1" id="birthYear" name="birth_year"
                                value="{{ old('birth_year') }}" autocomplete="bday-year" required>
                            <option {{ old('birth_year') ? '' : 'selected' }} disabled hidden>({{ __('Year') }})</option>
                            @foreach(range(now()->year - 17, now()->year - 120) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($errors->has('birth_*'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('birth_*') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="birth_place" class="col-sm-4 col-form-label font-weight-bold">{{ __('Birthplace') }}*</label>

                <div class="col-sm-8">
                    <input id="birth_place" type="text" class="form-control{{ $errors->has('birth_place') ? ' is-invalid' : '' }}" name="birth_place"
                           value="{{ old('birth_place') }}" required>

                    @if ($errors->has('birth_place'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('birth_place') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="address_street" class="col-sm-4 col-form-label">{{ __('Address') }}</label>

                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-8">
                            <input id="address_street" type="text" class="form-control{{ $errors->has('address_street') ? ' is-invalid' : '' }}" name="address_street"
                                   value="{{ old('address_street') }}" autocomplete="address-line1" placeholder="Musterstraße">
                        </div>
                        <div class="col-4">
                            <input id="address_number" type="text" class="form-control{{ $errors->has('address_number') ? ' is-invalid' : '' }}" name="address_number"
                                   value="{{ old('address_number') }}" placeholder="1">
                        </div>
                    </div>

                    @if ($errors->has('address_street'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('address_street') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('address_number'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('address_number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="address_addition" class="col-sm-4 col-form-label">{{ __('Address Addition') }}</label>

                <div class="col-sm-8">
                    <input id="address_addition" type="text" class="form-control{{ $errors->has('address_addition') ? ' is-invalid' : '' }}" name="address_addition"
                           value="{{ old('address_addition') }}" autocomplete="address-line2" placeholder="Haus 1 (optional)">

                    @if ($errors->has('address_addition'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('address_addition') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="address_zipcode" class="col-sm-4 col-form-label font-weight-bold">{{ __('ZIP Code') }}*</label>

                <div class="col-sm-8">
                    <input id="address_zipcode" type="text" class="form-control{{ $errors->has('address_zipcode') ? ' is-invalid' : '' }}" name="address_zipcode"
                           value="{{ old('address_zipcode') }}" autocomplete="postal-code" required>

                    @if ($errors->has('address_zipcode'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('address_zipcode') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="address_city" class="col-sm-4 col-form-label font-weight-bold">{{ __('City') }}*</label>

                <div class="col-sm-8">
                    <input id="address_city" type="text" class="form-control{{ $errors->has('address_city') ? ' is-invalid' : '' }}" name="address_city"
                           value="{{ old('address_city') }}" autocomplete="address-level2" required>

                    @if ($errors->has('address_city'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('address_city') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label font-weight-bold">{{ __('E-Mail Address') }}*</label>

                <div class="col-sm-8">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" autocomplete="email" required>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="phone" class="col-sm-4 col-form-label font-weight-bold">{{ __('Telephone') }}*</label>

                <div class="col-sm-8">
                    <input id="phone" type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                           name="phone" value="{{ old('phone') }}" autocomplete="tel-national" required>

                    @if ($errors->has('phone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg">
            <div class="form-group row">
                <label for="website" class="col-sm-4 col-form-label">{{ __('Your Website') }}</label>

                <div class="col-sm-8">
                    <input id="website" type="url" class="form-control{{ $errors->has('website') ? ' is-invalid' : '' }}" name="website"
                           value="{{ old('website') }}">

                    @if ($errors->has('website'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('website') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="vat_id" class="col-sm-4 col-form-label">{{ __('VAT ID') }}</label>

                <div class="col-sm-8">
                    <input id="vat_id" type="text" class="form-control{{ $errors->has('vat_id') ? ' is-invalid' : '' }}" name="vat_id"
                           value="{{ old('vat_id') }}">

                    @if ($errors->has('vat_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('vat_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="tax_office" class="col-sm-4 col-form-label">{{ __('Tax Office') }}</label>

                <div class="col-sm-8">
                    <input id="tax_office" type="text" class="form-control{{ $errors->has('tax_office') ? ' is-invalid' : '' }}" name="tax_office"
                           value="{{ old('tax_office') }}">

                    @if ($errors->has('tax_office'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('tax_office') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label font-weight-bold">{{ __('Password') }}*</label>

                <div class="col-sm-8">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           name="password" required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-sm-4 col-form-label font-weight-bold">{{ __('Confirm Password') }}*</label>

                <div class="col-sm-8">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <div class="form-group mt-4">
                <p>
                    Mit der Anmeldung zum Exporo Partnerprogramm schließen Sie einen Partnervertrag
                    mit der Exporo AG und der Exporo Investment GmbH.
                </p>

                <p>Sie erklären sich mit den</p>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="legal_exporo_ag" name="legal_exporo_ag"
                           {{ old('legal_exporo_ag') !== null ? 'checked' : '' }} required>
                    <label class="custom-control-label" for="legal_exporo_ag">
                        AGB & Datenschutzerklärung der Exporo AG
                    </label>
                </div>

                @if ($errors->has('legal_exporo_ag'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('llegal_exporo_ag') }}</strong>
                    </span>
                @endif

                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="legal_exporo_gmbh" name="legal_exporo_gmbh"
                           {{ old('legal_exporo_gmbh') !== null ? 'checked' : '' }} required>
                    <label class="custom-control-label" for="legal_exporo_gmbh">
                        den AGB & Datenschutzerklärung der Exporo Investment GmbH
                        sowie den Bestimmungen zu Cookies & Internet-Werbung einverstanden.
                    </label>
                </div>

                @if ($errors->has('legal_exporo_gmbh'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('laslegal_exporo_gmbh') }}</strong>
                    </span>
                @endif

                <div class="custom-control custom-checkbox mt-4">
                    <input type="checkbox" class="custom-control-input" id="legal_transfer" name="legal_transfer"
                           {{ old('legal_transfer') !== null ? 'checked' : '' }} required>
                    <label class="custom-control-label" for="legal_transfer">
                        Sie sind bin mit der Weitergabe Ihrer personenbezogenen Daten von der
                        Exporo Investment GmbH an die Exporo AG zum Zwecke der Verwaltung Ihrer
                        personenbezogenen Daten und der Abrechnung Ihrer Vergütung einverstanden.
                    </label>
                </div>

                @if ($errors->has('legal_transfer'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('legal_transfer') }}</strong>
                    </span>
                @endif
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
