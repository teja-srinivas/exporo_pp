<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Company;
use App\Http\Requests\UserStoreRequest;
use App\Services\EmailService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'documents',
            'agbs' => function ($query) {
                $query->latest();
            },
        ]);

        $user->bills = Bill::getDetailsPerUser($user->id)->get();

        $investors = $user->investors()->toBase()
            ->leftJoin('investments', 'investments.investor_id', 'investors.id')
            ->selectRaw('count(distinct(investors.id)) as count')
            ->selectRaw('count(investments.id) as investments')
            ->selectRaw('sum(investments.amount) as amount')
            ->first();

        return response()->view('users.show', compact('user', 'investors'));
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
            $email = new EmailService();
            $field = $attributes['accept']? 'accepted_at' : 'rejected_at';
            if($field === 'accepted_at'){
                $email->SendMail([
                    'Vorname' => $user->first_name,
                    'Nachname' => $user->last_name,
                    'Login' => route('login'),
                ], $user, config('mail.templateIds.approved'));
            }
            elseif ($field === 'rejected_at'){
                $email->SendMail([
                    'Anrede' => $user->salutation,
                    'Nachname' => $user->last_name,
                ], $user, config('mail.templateIds.rejected'));
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
}
