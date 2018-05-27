@include('components.form.builder', ['inputs' => [
    [
        'name' => 'first_name',
        'label' => __('First Name'),
        'autocomplete' => 'given-name',
        'required' => true,
        'default' => optional($user ?? null)->first_name,
    ],
    [
        'name' => 'last_name',
        'label' => __('Last Name'),
        'autocomplete' => 'family-name',
        'required' => true,
        'default' => optional($user ?? null)->last_name,
    ],
    [
        'type' => 'email',
        'name' => 'email',
        'label' => __('E-Mail Address'),
        'autocomplete' => 'email',
        'required' => true,
        'default' => optional($user ?? null)->email,
    ],
]])
