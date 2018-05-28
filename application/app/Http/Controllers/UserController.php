<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\UserDetails;
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
        $this->authorize('view users');

        $users = User::ordered()->latest()->get();

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
        $this->authorize('create users');

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
        $this->authorize('create users');

        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email',
        ]);

        $data['password'] = Hash::make(str_random());

        $user = User::create($data);

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
        $this->authorize('view users');

        $user->load([
            'documents',
            'agbs' => function ($query) {
                $query->latest();
            },
            'investors' => function ($query) {
                $query->orderBy('last_name');
            }
        ]);

        return response()->view('users.show', compact('user'));
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
        if ($user->isNot(auth()->user())) {
            $this->authorize('edit users');
        }

        return response()->view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, User $user)
    {
        if ($user->isNot(auth()->user())) {
            $this->authorize('edit users');
        }

        $user->fill($request->validate(User::getValidationRules($user)))->saveOrFail();
        $user->details->fill($request->validate(UserDetails::getValidationRules(false)))->saveOrFail();

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
        $this->authorize('destroy users');

        $user->delete();

        return redirect()->route('users.index');
    }
}
