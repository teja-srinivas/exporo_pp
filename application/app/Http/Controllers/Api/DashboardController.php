<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\Person;
use App\Models\Investment;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    /**
     * @param  Request  $request
     * @return Array
     * @throws ValidationException
     */
    public function getInvestments(Request $request)
    {
        $user = $request->user();

        $data = $this->validate($request, [
            'period' => ['nullable', 'in:this_month,last_month,default,custom'],
        ]);

        if (isset($data['period'])) {
            switch ($data['period']) {
                case 'this_month':
                    $periodFrom = Carbon::now()->startOfMonth();

                    break;
                case 'last_month':
                    $periodFrom = Carbon::now()->startOfMonth()->subMonth();

                    break;
                case 'custom':
                    $periodFrom = Carbon::create($request->first)->startOfDay();
                    $periodTo = Carbon::create($request->second)->endOfDay();

                    break;
                default:
                    $periodFrom = Carbon::now()->subDays(30);
            }
        }

        $investmentQuery = $user->investments();

        if (isset($periodFrom)) {
            $investmentQuery->where('investments.created_at', '>=', $periodFrom);
        }

        if (isset($periodTo)) {
            $investmentQuery->where('investments.created_at', '<=', $periodTo);
        }

        $investmentQuery->whereNull('investments.cancelled_at');
        $investmentQuery->orderBy('investments.created_at', 'DESC');
        $investments = $investmentQuery->get()
            ->map(static function (Investment $investment) {
                $investor = Person::anonymizeName(
                    $investment->investor->first_name,
                    $investment->investor->last_name
                );

                return [
                    'amount' => $investment->amount,
                    'is_first_investment' => $investment->is_first_investment,
                    'project_name' => $investment->project->name,
                    'created_at' => $investment->created_at,
                    'investment_type' => $investment->is_first_investment ? 'first' : 'subsequent',
                    'investor' => $investor,
                    'investor_id' => $investment->investor_id,
                    'provision_type' => $investment->type,
                ];
            })->all();

        $commissionQuery = $user->commissions();

        if (isset($periodFrom)) {
            $commissionQuery->where('commissions.created_at', '>=', $periodFrom);
        }

        if (isset($periodTo)) {
            $commissionQuery->where('commissions.created_at', '<=', $periodTo);
        }

        $commissionQuery->whereNotNull('bill_id');
        $commissions = $commissionQuery->get()
            ->map(static function (Commission $commission) {
                return [
                    'amount' => $commission->gross,
                    'created_at' => $commission->created_at,
                ];
            })->all();

        return [
            'investments' => $investments,
            'commissions' => $commissions,
            'draw' => $request->draw,
        ];
    }
}
