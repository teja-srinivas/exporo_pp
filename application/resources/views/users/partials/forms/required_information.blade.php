<div class="form-group row">
    <label for="inputFirstName" class="col-sm-3 col-form-label">{{ __('First Name') }}:</label>
    <div class="col-sm-9">
        @include('components.form.input', [
            'name' => 'first_name',
            'autocomplete' => 'given-name',
            'required' => true,
            'default' => optional($user ?? null)->first_name,
        ])
    </div>
</div>

<div class="form-group row">
    <label for="inputLastName" class="col-sm-3 col-form-label">{{ __('Last Name') }}:</label>
    <div class="col-sm-9">
        @include('components.form.input', [
            'name' => 'last_name',
            'autocomplete' => 'family-name',
            'required' => true,
            'default' => optional($user ?? null)->last_name,
        ])
    </div>
</div>

<div class="form-group row mb-0">
    <label for="inputEmail" class="col-sm-3 col-form-label">{{ __('E-Mail Address') }}:</label>
    <div class="col-sm-9">
        @include('components.form.input', [
            'name' => 'email',
            'autocomplete' => 'email',
            'required' => true,
            'default' => optional($user ?? null)->email,
        ])
    </div>
</div>
