<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Jobs\SendMail;
use App\Models\Bill;
use App\Models\BonusBundle;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('list', User::class);

        $users = User::query()->latest()->with('roles')->get()->sortNatural('last_name');

        return response()->view('users.index', compact('users'));
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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email',
        ]);

        $data['password'] = Hash::make(str_random());
        $data['company_id'] = Company::first()->getKey();

        $user = User::forceCreate($data);

        return redirect()->route('users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load([
            'bonuses.type',
            'documents',
            'agbs' => function ($query) {
                $query->latest();
            },
        ]);

        $user->bills = Bill::getDetailsPerUser($user->id)->get();

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

        $bonusBundles = BonusBundle::all()->mapWithKeys(function (BonusBundle $bundle) {
            return [$bundle->getKey() => $bundle->name];
        });

        return response()->view('users.show', compact('user', 'investors', 'bonusBundles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return response()->view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserStoreRequest $request
     * @param User $user
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(UserStoreRequest $request, User $user)
    {
        $attributes = $request->validated();
        if (isset($attributes['accept'])) {
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

        $user->fill($attributes)->saveOrFail();
        $user->details->fill($attributes)->saveOrFail();

        flash_success();

        return redirect()->route('users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index');
    }

    public function loginUsingId(User $user)
    {
        Auth::loginUsingId($user->id, true);

        return redirect()->route('home');
    }
}
