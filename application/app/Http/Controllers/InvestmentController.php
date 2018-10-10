<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return view('investments.index', [
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
