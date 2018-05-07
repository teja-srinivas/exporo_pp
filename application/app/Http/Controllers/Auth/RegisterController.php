<?php

namespace App\Http\Controllers\Auth;

use App\Agb;
use App\Role;
use App\Rules\VatId;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['birth_date'] = $this->makeBirthDate($data);

        return Validator::make($data, [
            'company' => 'nullable|string|max:100',
            'title' => ['nullable', Rule::in(User::TITLES)],
            'salutation' => 'required|in:male,female',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(18), // needs to be an adult
            'birth_place' => 'required|string|max:100',
            'address_street' => 'nullable|string|max:100',
            'address_number' => 'nullable|string|max:20',
            'address_addition' => 'nullable|string|max:100',
            'address_zipcode' => 'nullable|string|max:20',
            'address_city' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:100',
            'website' => 'nullable|string|max:100',
            'vat_id' => ['nullable', new VatId()],
            'tax_office' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'legal_exporo_ag' => 'accepted',
            'legal_exporo_gmbh' => 'accepted',
            'legal_transfer' => 'accepted',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->details()->create([
            'company' => $data['company'],
            'title' => $data['title'],
            'salutation' => $data['salutation'],
            'birth_date' => $this->makeBirthDate($data),
            'birth_place' => $data['birth_place'],
            'address_street' => $data['address_street'],
            'address_number' => $data['address_number'],
            'address_addition' => $data['address_addition'],
            'address_zipcode' => $data['address_zipcode'],
            'address_city' => $data['address_city'],
            'phone' => $data['phone'],
            'website' => $data['website'],
            'vat_id' => $data['vat_id'],
            'tax_office' => $data['tax_office'],
        ]);

        $user->assignRole(Role::PARTNER);
        $user->agbs()->attach(Agb::current());

        return $user;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function makeBirthDate(array $data): string
    {
        return sprintf(
            '%s-%s-%s',
            $data['birth_year'] ?? '',
            $data['birth_month'] ?? '',
            $data['birth_day'] ?? ''
        );
    }
}
