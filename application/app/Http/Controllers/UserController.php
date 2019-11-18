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
use App\Repositories\BillRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  UserRepository  $userRepository
     * @return \Illuminate\Http\Response
     */
    public function index(UserRepository $userRepository)
    {
        return response()->view('users.index', [
            'users' => $userRepository->forTableView(),
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
     * @param User $user
     * @param BillRepository $bills
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, BillRepository $bills)
    {
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
        $user->delete();

        return redirect()->route('users.index');
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

        Auth::loginUsingId($user->id, true);

        return redirect()->route('home');
    }
}
