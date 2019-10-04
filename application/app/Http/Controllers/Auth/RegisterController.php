<?php

namespace App\Http\Controllers\Auth;

use App\Models\Agb;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Contract;
use Illuminate\Support\Str;
use App\Models\CommissionBonus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\UserHasBeenReferred;
use Illuminate\Foundation\Auth\RegistersUsers;

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
                'cookie_advertisement' => 'accepted',
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

        /** @var Company $company */
        $company = Company::query()->first();

        return DB::transaction(function () use ($company, $data, $agbs) {
            /** @var User $user */
            $user = User::query()->forceCreate([
                'company_id' => $company->getKey(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'parent_id' => request()->cookie(UserHasBeenReferred::COOKIE_NAME, '0'),
                'api_token' => Str::random(32),
            ]);

            $user->details->fill([
                'company' => $data['company'] ?? null,
                'title' => $data['title'] ?? null,
                'salutation' => $data['salutation'] ?? null,
                'birth_date' => UserStoreRequest::makeBirthDate($data),
                'address_street' => $data['address_street'] ?? null,
                'address_number' => $data['address_number'] ?? null,
                'address_addition' => $data['address_addition'] ?? null,
                'address_zipcode' => $data['address_zipcode'] ?? null,
                'address_city' => $data['address_city'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website' => $data['website'] ?? null,
            ])->saveOrFail();

            $user->agbs()->attach($agbs);
            $user->assignRole(Role::PARTNER);

            $contract = Contract::fromTemplate($company->contractTemplate);
            $user->contract()->save($contract);

            $contract->bonuses()->saveMany($company->contractTemplate->bonuses->map(static function (CommissionBonus $bonus) {
                return $bonus->replicate();
            }));

            session()->put('trackUserRegistration', true);

            return $user;
        });
    }
}
