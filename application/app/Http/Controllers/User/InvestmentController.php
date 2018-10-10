<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Request $request)
    {
        $viewer = $request->user();

        abort_if($user->isNot($viewer) && $viewer->cannot(UserPolicy::PERMISSION), 404);

        return view('users.investments.index', [
            'user' => $user,
            'investments' => $user->investments()
                ->rightJoin('projects', 'projects.id', 'investments.project_id')
                ->select('investments.id', 'paid_at', 'investments.type', 'amount')
                ->selectRaw('investors.first_name')
                ->selectRaw('investors.last_name')
                ->selectRaw('projects.name as project_name')
                ->latest('investments.created_at')
                ->get(),
        ]);
    }
}
