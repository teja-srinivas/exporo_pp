<?php

namespace App\Http\Controllers;

use App\Commission;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->view('home', [
            'paid' => Commission::query()
                ->join('bills', 'bill_id', 'bills.id')
                ->where('commissions.user_id', $request->user()->id)
                ->where('released_at', '<=', now())
                ->sum('net'),
        ]);
    }
}
