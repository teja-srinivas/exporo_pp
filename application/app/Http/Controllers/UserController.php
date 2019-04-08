<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Jobs\SendMail;
use App\Models\BonusBundle;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\BillRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(UserRepository $userRepository)
    {
        $this->authorize('viewAny', User::class);

        return response()->view('users.index', [
            'users' => $userRepository->forTableView(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return response()->view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Hasher $hasher
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Hasher $hasher)
    {
        $this->authorize('create', User::class);

        $data = $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email',
        ]);

        $data['password'] = $hasher->make(Str::random());
        $data['company_id'] = Company::query()->first()->getKey();

        $user = User::query()->forceCreate($data);

        return redirect()->route('users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param BillRepository $bills
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user, BillRepository $bills)
    {
        $this->authorize('view', $user);

        $user->load([
            'bonuses.type',
            'documents',
            'agbs' => function (BelongsToMany $query) {
                $query->latest();
            },
        ]);

        $user->bills = $bills->getDetails($user->id)->sortByDesc('created_at');

        $investors = $user->investors()
            ->leftJoin('investments', 'investments.investor_id', 'investors.id')
            ->selectRaw('count(distinct(investors.id)) as count')
            ->selectRaw('count(investments.id) as investments')
            ->selectRaw('sum(investments.amount) as amount')
            ->selectSub($user->investments()->toBase()
                ->where(function (Builder $query) {
                    $query->where('investments.cancelled_at', LEGACY_NULL);
                    $query->orWhereNull('investments.cancelled_at');
                })
                ->selectRaw('sum(amount)'), 'amount')
            ->first();

        $bonusBundles = BonusBundle::all()->groupBy(function (BonusBundle $bundle) {
            return $bundle->selectable ? 'Aktiv / Öffentlich' : 'Intern / Versteckt / Archiv';
        })->map->mapWithKeys(function (BonusBundle $bundle) {
            return [$bundle->getKey() => $bundle->name];
        });

        return response()->view('users.show', compact('user', 'investors', 'bonusBundles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $data['user'] = $user;

        if ($request->user()->can('manage', User::class)) {
            $data['roles'] = Role::query()->orderBy('name')->get();
            $data['permissions'] = Permission::all()->reject(function (Permission $permission) use ($user) {
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

        if (isset($attributes['accept']) && $user->canBeAccepted()) {
            $field = $attributes['accept'] ? 'accepted_at' : 'rejected_at';

            if ($field === 'accepted_at') {
                /** @var PasswordBroker $broker */
                $broker = app(PasswordBroker::class);
                SendMail::dispatch([
                    'Login' => route('password.reset', $broker->createToken($user)),
                ], $user, config('mail.templateIds.approved'))->onQueue('emails');
            } elseif ($field === 'rejected_at') {
                SendMail::dispatch([
                ], $user, config('mail.templateIds.declined'))->onQueue('emails');
            }

            $user->setAttribute($field, now());
        }

        if (isset($attributes['bonusBundle'])) {
            $user->switchToBundle(BonusBundle::query()->findOrFail($attributes['bonusBundle']));
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index');
    }

    public function loginUsingId(User $user, Session $session, Request $request)
    {
        static $id = 'adminOriginalId';
        static $link = 'adminLoginLink';

        $originalId = $session->get($id);
        $originalUser = $request->user();

        if ((string)$originalId === (string)$user->getAuthIdentifier()) {
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
