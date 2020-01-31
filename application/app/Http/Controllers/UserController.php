<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ContractTemplate;
use Illuminate\Support\Facades\DB;
use App\Repositories\BillRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommissionBonus;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Session\Session;
use App\Models\CommissionBonus as Bounus;
use App\Http\Middleware\UserHasFilledPersonalData;
use App\Http\Middleware\RequireAcceptedPartnerContract;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, null, [
            'except' => [ 'show' ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  UserRepository  $userRepository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserRepository $userRepository)
    {
        $query = null;

        if ($request->user()->can('delete', new User())) {
            $query = User::withTrashed();
        }

        return response()->view('users.index', [
            'users' => $userRepository->forTableView($query),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $contractTemplates = ContractTemplate::all()
            ->groupBy('type')
            ->map->pluck('name', 'id');

        return response()->view(
            'users.create',
            compact('user', 'contractTemplates')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserStoreRequest $request
     * @param Hasher $hasher
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserStoreRequest $request, Hasher $hasher)
    {
        $user = DB::transaction(static function () use ($request, $hasher) {
            $company = Company::query()->first();

            $user = new User($request->validated());
            $user->assignRole(Role::PARTNER);
            $user->password = $hasher->make(Str::random());
            $user->company()->associate($company);
            $user->save();

            $user->details->fill($request->validated())->saveOrFail();

            $company->createContractsFor($user, $request->contracts);

            return $user;
        });

        // Send DOI mail manually
        $user->sendEmailVerificationNotification();

        return redirect()->route('users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param String $id
     * @param BillRepository $bills
     * @return \Illuminate\Http\Response
     */
    public function show($id, BillRepository $bills, Request $request)
    {
        $this->authorize('view', new User());

        $user = User::withTrashed()->find($id);

        if ($user === null) {
            abort(404);
        }

        if (!$request->user()->can('delete', new User()) && $user->trashed()) {
            abort(404);
        }

        $user->load(['documents']);

        $user->bills = $bills->getDetails($user->id)->latest()->get();

        $investors = $user->investors()
            ->leftJoin('investments', 'investments.investor_id', 'investors.id')
            ->selectRaw('count(distinct(investors.id)) as count')
            ->selectRaw('count(investments.id) as investments')
            ->selectRaw('sum(investments.amount) as amount')
            ->selectSub($user->investments()->toBase()
                ->whereNull('investments.cancelled_at')
                ->selectRaw('sum(amount)'), 'amount')
            ->first();

        $contractTemplates = ContractTemplate::all()
            ->groupBy('type')
            ->map->pluck('name', 'id')
            ->mapWithKeys(static function ($contents, string $name) {
                return [__("contracts.{$name}.title") => $contents];
            })
            ->sort();

        $contracts = $user->contracts()
            ->orderByDesc('accepted_at')
            ->latest()
            ->get();

        return response()->view('users.show', compact('user', 'contracts', 'investors', 'contractTemplates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Request $request)
    {
        $data = compact('user');

        if ($request->user()->can('manage', User::class)) {
            $data['roles'] = Role::query()->orderBy('name')->get();
            $data['permissions'] = Permission::all()->reject(static function (Permission $permission) use ($user) {
                return $permission->isProtected($user->roles);
            });
        }

        return response()->view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserStoreRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(UserStoreRequest $request, User $user)
    {
        $attributes = $request->validated();

        if (isset($attributes['accept'])) {
            $field = $attributes['accept'] ? 'accepted_at' : 'rejected_at';
            $user->setAttribute($field, now());
        }

        // Check if we have a permissions update
        // TODO this will still fail if we want to clear both, roles and permissions
        if ($request->has('roles') || $request->has('permissions')) {
            $user->syncPermissions(array_keys(array_filter($attributes['permissions'] ?? [])));
            $user->syncRoles(array_keys(array_filter($attributes['roles'] ?? [])));
        }

        $user->fill($attributes)->saveOrFail();
        $user->details->fill($attributes)->saveOrFail();

        flash_success();

        if ($request->get('redirect') === 'back') {
            return back();
        }

        return redirect()->route('users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        DB::transaction(static function () use ($user) {
            if ($user->productContract !== null) {
                $user->productContract
                    ->whereNull('terminated_at')
                    ->update(['terminated_at' => now()]);
            }

            if ($user->partnerContract !== null) {
                $user->partnerContract
                    ->whereNull('terminated_at')
                    ->update(['terminated_at' => now()]);
            }

            $user->delete();
        });

        $name = $user->details->display_name;

        flash_success("$name wurde gelöscht.");
        
        return redirect()->route('users.index');
    }

    /**
     * Restore the specified resource.
     *
     * @param String $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore($id)
    {
        $this->authorize('delete', new User());

        $user = User::withTrashed()->find($id);

        DB::transaction(static function () use ($user) {
            $user->restore();

            $productContract = $user->productContract;

            if ($productContract !== null) {
                $newProductContract = $productContract->template->createInstance($user);
                $newProductContract->update([
                    'special_agreement' => $productContract->special_agreement,
                ]);

                $bonuses = $productContract->bonuses()
                    ->get()
                    ->map(static function (Bounus $bonus) {
                        return [
                            'type_id' => $bonus->type_id,
                            'calculation_type' => $bonus->calculation_type,
                            'value' => $bonus->value,
                            'is_percentage' => $bonus->is_percentage,
                            'is_overhead' => $bonus->is_overhead,
                        ];
                    });

                foreach ($bonuses as $bonus) {
                    CommissionBonus::make($newProductContract->bonuses()->create($bonus));
                }

                $newProductContract->released_at = now();
                $newProductContract->save();
            }

            $partnerContract = $user->partnerContract;

            if ($partnerContract === null) {
                return;
            }

            $newPartnerContract = $partnerContract->template->createInstance($user);

            $newPartnerContract->update([
                'special_agreement' => $partnerContract->special_agreement,
                'is_exclusive' => $partnerContract->is_exclusive,
                'allow_overhead' => $partnerContract->allow_overhead,
            ]);

            $newPartnerContract->released_at = now();
            $newPartnerContract->save();
        });

        $name = $user->details->display_name;

        flash_success("$name wurde wiederhergestellt.");

        return redirect()->route('users.show', [$user->id]);
    }

    public function loginUsingId(User $user, Session $session, Request $request)
    {
        static $id = 'adminOriginalId';
        static $link = 'adminLoginLink';

        $originalId = $session->get($id);
        $originalUser = $request->user();

        if ((string) $originalId === (string) $user->getAuthIdentifier()) {
            // We've returned to our original user account, remove the warning(s)
            $session->remove($link);
            $session->remove($id);
        } elseif ($originalUser !== null) {
            // Remember a link to return to our profile and keep the "original" user ID for reference
            $session->put($link, $originalUser->getLoginLink());

            if ($originalId === null) {
                $session->put($id, $originalUser->getAuthIdentifier());
            }
        }

        session()->forget([
            UserHasFilledPersonalData::USER_HAS_MISSING_DATA,
            RequireAcceptedPartnerContract::SESSION_KEY,
        ]);

        Auth::loginUsingId($user->id, true);

        return redirect()->route('home');
    }
}
