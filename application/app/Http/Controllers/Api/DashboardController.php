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
        $this->authorize('management.dashboard.view');

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
                    $periodTo = Carbon::now()->startOfMonth();

                    break;
                case 'custom':
                    $periodFrom = isset($request->first) ? Carbon::create($request->first)->startOfDay() : null;
                    $periodTo = isset($request->second) ? Carbon::create($request->second)->endOfDay() : null;

                    break;
                default:
                    $periodFrom = Carbon::now()->subDays(30);
            }
        } else {
            $periodTo = Carbon::now();
        }

        $investmentQuery = $user->investments();
        $investmentQuery->join('projects', 'investments.project_id', 'projects.id');
        $investmentQuery->select(
            'investments.created_at',
            'investments.amount',
            'investments.is_first_investment',
            'investments.investor_id',
            'investors.last_name',
            'investors.first_name',
            'projects.type as project_type',
            'projects.description'
        );

        if (isset($periodFrom)) {
            $investmentQuery->where('investments.created_at', '>=', $periodFrom);
        }

        if (isset($periodTo)) {
            $investmentQuery->where('investments.created_at', '<=', $periodTo);
        }

        $investmentQuery->whereIn('projects.type', ['Exporo Financing', 'Exporo Bestand']);
        $investmentQuery->whereNull('investments.cancelled_at');
        $investmentQuery->orderBy('investments.created_at', 'DESC');
        $investments = $investmentQuery->get()
            ->map(static function (Investment $investment) {
                $investor = Person::anonymizeName(
                    decrypt($investment->first_name),
                    decrypt($investment->last_name)
                );

                switch ($investment->project_type) {
                    case "Exporo Financing":
                        $type = "Exporo Finanzierung";

                        break;
                    case "Exporo Bestand":
                        $type = "Exporo Bestand";

                        break;
                    default:
                        $type = null;
                }

                return [
                    'amount' => $investment->amount,
                    'is_first_investment' => $investment->is_first_investment,
                    'project_name' => $investment->description,
                    'created_at' => $investment->created_at,
                    'investment_type' => $investment->is_first_investment ? 'first' : 'subsequent',
                    'investor' => $investor,
                    'investor_id' => $investment->investor_id,
                    'project_type' => $type,
                ];
            })->all();

        return [
            'investments' => $investments,
            'draw' => $request->draw,
        ];
    }

    /**
     * @param  Request  $request
     * @return Array
     * @throws ValidationException
     */
    public function getCommissions(Request $request)
    {
        $this->authorize('management.dashboard.view');

        $user = $request->user();

        $data = $this->validate($request, [
            'period' => ['nullable', 'in:this_month,last_month,default,custom'],
        ]);

        if (isset($data['period'])) {
            switch ($data['period']) {
                case 'this_month':
                    $periodFrom = Carbon::now()->startOfMonth();
                    $periodTo = Carbon::now();

                    break;
                case 'last_month':
                    $periodFrom = Carbon::now()->startOfMonth()->subMonth();
                    $periodTo = Carbon::now()->startOfMonth();

                    break;
                case 'custom':
                    $periodFrom = isset($request->first) ? Carbon::create($request->first)->startOfDay() : null;
                    $periodTo = isset($request->second) ? Carbon::create($request->second)->endOfDay() : null;

                    break;
                default:
                    $periodFrom = Carbon::now()->subDays(30);
                    $periodTo = Carbon::now();
            }
        } else {
            $periodTo = Carbon::now();
        }

        $baseQuery = $user->commissions();
        $baseQuery->join('bills', 'commissions.bill_id', 'bills.id');
        $baseQuery->selectRaw(
            'bills.created_at, SUM(commissions.gross) as gross'
        );

        $baseQuery->groupBy('bills.created_at');
        $baseQuery->whereNotNull('bill_id');
        $baseQuery->where('gross', '>=', 0);

        $commissionQuery = $baseQuery;
    
        if (isset($periodFrom)) {
            $secondDate = isset($request->second) ? Carbon::create($request->second)->endOfDay() : Carbon::now();

            if ($periodFrom->diffInMonths($secondDate) < 6) {
                $periodFrom = $secondDate->endOfDay()->subMonths(6);
            }

            $commissionQuery->where('bills.created_at', '>=', $periodFrom);
        }

        if (isset($periodTo)) {
            $commissionQuery->where('bills.created_at', '<=', $periodTo);
        }

        $commissions = $commissionQuery->get();

        if (count($commissions) < 6) {
            $fallbackQuery = $baseQuery->where('bills.created_at', '<=', $periodTo);
            $fallbackQuery->orderBy('bills.created_at', 'desc');
            $commissions = $fallbackQuery->take(6)->get();
        }

        $mapped = $commissions->map(static function (Commission $commission) {
            return [
                'amount' => $commission->gross,
                'created_at' => $commission->created_at,
            ];
        })->all();

        return [
            'commissions' => $mapped,
            'draw' => $request->draw,
        ];
    }
}
