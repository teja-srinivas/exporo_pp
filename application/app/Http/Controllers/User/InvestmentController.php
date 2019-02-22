<?php

namespace App\Http\Controllers\User;

use App\Models\Investment;
use App\Models\User;
use App\Traits\Encryptable;
use App\Traits\Person;
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

        return response()->view('users.investments.index', [
            'user' => $user,
            'investments' => $user->investments()
                ->join('projects', 'projects.id', 'investments.project_id')
                ->join('schemas', 'schemas.id', 'projects.schema_id')
                ->select('investments.id', 'paid_at', 'amount', 'cancelled_at')
                ->selectRaw('investors.first_name')
                ->selectRaw('investors.last_name')
                ->selectRaw('projects.description as project_name')
                ->selectRaw('schemas.name as type')
                ->selectRaw('investments.created_at')
                ->latest('investments.created_at')
                ->get()
                ->map(function (Investment $investment) {
                    $cancelled = $investment->isCancelled();
                    $firstName = Encryptable::decrypt($investment->first_name);
                    $lastName = Encryptable::decrypt($investment->last_name);

                    return [
                        'id' => $investment->id,
                        'name' => $lastName . ' ' . Person::anonymizeFirstName($firstName),
                        'displayName' => Person::anonymizeName($firstName, $lastName),
                        'projectName' => $investment->project_name,
                        'type' => $investment->type,
                        'amount' => $cancelled ? null : $investment->amount,
                        'paidAt' => $cancelled ? '' : optional($investment->paid_at)->format('Y-m-d'),
                        'createdAt' => optional($investment->created_at)->format('Y-m-d'),
                    ];
                }),
        ]);
    }
}
