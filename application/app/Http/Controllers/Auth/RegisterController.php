<?php

namespace App\Http\Controllers\Auth;

use App\Agb;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Role;
use App\User;
use App\UserDetails;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $data['birth_date'] = UserStoreRequest::makeBirthDate($data);

        return Validator::make($data, array_merge(
            User::getValidationRules(),
            UserDetails::getValidationRules(),
            [
                'password' => 'required|string|min:6|confirmed',
                'legal_exporo_ag' => 'accepted',
                'legal_exporo_gmbh' => 'accepted',
                'legal_transfer' => 'accepted',
            ]
        ));
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
            'birth_date' => UserStoreRequest::makeBirthDate($data),
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

        $agb = Agb::current();

        if ($agb !== null) {
            $user->agbs()->attach($agb);
        }

        return $user;
    }
}
