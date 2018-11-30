<?php

namespace App\Http\Controllers\User;

use App\Models\Investment;
use App\Models\User;
use App\Traits\Encryptable;
use App\Traits\Person;
use Illuminate\Database\Eloquent\Builder;
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
        $this->authorizeViewingUser($user, $request);

        return view('users.investments.index', [
            'user' => $user,
            'investments' => $user->investments()
                ->join('projects', 'projects.id', 'investments.project_id')
                ->join('schemas', 'schemas.id', 'projects.schema_id')
                ->select('investments.id', 'paid_at', 'amount', 'cancelled_at')
                ->selectRaw('investors.first_name')
                ->selectRaw('investors.last_name')
                ->selectRaw('projects.description as project_name')
                ->selectRaw('schemas.name as type')
                ->latest('investments.created_at')
                ->get()->each(function (Investment $investment) {
                    $investment->name = Person::anonymizeName(
                        Encryptable::decrypt($investment->first_name),
                        Encryptable::decrypt($investment->last_name)
                    );

                    return $investment;
                }),
        ]);
    }
}
