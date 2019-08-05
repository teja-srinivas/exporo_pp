<?php

namespace App\Http\Controllers\Auth;

use App\Models\Agb;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Contract;
use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use App\Models\ContractTemplate;
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
            ]);

            $user->details->fill([
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
            ])->saveOrFail();

            $user->agbs()->attach($agbs);
            $user->assignRole(Role::PARTNER);

            $contract = Contract::fromTemplate($company->contractTemplate);
            $user->contract()->save($contract);

            $bundle = BonusBundle::query()->selectable($user->parent_id > 0)->first();
            $bundle->bonuses->each(function (CommissionBonus $bonus) use ($contract) {
                $copy = $bonus->replicate(['contract_id']);
                $copy->contract()->associate($contract);
                $copy->accepted_at = now();
                $copy->saveOrFail();

                return $copy;
            });

            return $user;
        });
    }
}
