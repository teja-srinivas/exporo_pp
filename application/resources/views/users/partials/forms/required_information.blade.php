<div class="form-group row">
    <label for="inputFirstName" class="col-sm-3 col-form-label">{{ __('First Name') }}:</label>
    <div class="col-sm-9">
        <input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
               id="inputFirstName" value="{{ old('first_name', optional($user ?? null)->first_name) }}"
               name="first_name" autocomplete="given-name" required>

        <span class="invalid-feedback">
            <strong>{{ $errors->first('first_name') }}</strong>
        </span>
    </div>
</div>

<div class="form-group row">
    <label for="inputLastName" class="col-sm-3 col-form-label">{{ __('Last Name') }}:</label>
    <div class="col-sm-9">
        <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
               id="inputLastName" value="{{ old('last_name', optional($user ?? null)->last_name) }}"
               name="last_name" autocomplete="family-name" required>

        <span class="invalid-feedback">
            <strong>{{ $errors->first('last_name') }}</strong>
        </span>
    </div>
</div>

<div class="form-group row mb-0">
    <label for="inputEmail" class="col-sm-3 col-form-label">{{ __('E-Mail Address') }}:</label>

    <div class="col-sm-9">
        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
               id="inputEmail" value="{{ old('email', optional($user ?? null)->email) }}"
               name="email" autocomplete="email" required>

        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    </div>
</div>
