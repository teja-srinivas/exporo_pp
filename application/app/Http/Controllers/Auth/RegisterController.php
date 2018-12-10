<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\UserHasBeenReferred;
use App\Http\Requests\UserStoreRequest;
use App\Models\Agb;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
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
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['birth_date'] = UserStoreRequest::makeBirthDate($data);

        return Validator::make($data, array_merge(
            UserStoreRequest::getUserValidationRules(),
            UserStoreRequest::getDetailValidationRules(),
            [
                'legal_exporo_ag' => 'accepted',
                'legal_exporo_gmbh' => 'accepted',
                'legal_transfer' => 'accepted',
            ]
        ));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $agbs = collect(Agb::TYPES)->map(function (string $type) {
            return Agb::current($type);
        })->filter()->pluck('id');

        $companyId = Company::query()->first()->getKey();

        return DB::transaction(function () use ($companyId, $data, $agbs) {
            /** @var User $user */
            $user = User::query()->forceCreate([
                'company_id' => $companyId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'parent_id' => request()->cookie(UserHasBeenReferred::COOKIE_NAME, 0),
            ]);

            $user->details()->create([
                'company' => $data['company'],
                'title' => $data['title'],
                'salutation' => $data['salutation'],
                'birth_date' => UserStoreRequest::makeBirthDate($data),
                'address_street' => $data['address_street'],
                'address_number' => $data['address_number'],
                'address_addition' => $data['address_addition'],
                'address_zipcode' => $data['address_zipcode'],
                'address_city' => $data['address_city'],
                'phone' => $data['phone'],
                'website' => $data['website'],
            ]);

            $user->assignRole(Role::PARTNER);
            $user->agbs()->attach($agbs);

            return $user;
        });
    }
}
