<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Investment;
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

        $query = $user->investments();
        if (isset($periodFrom)) {
            $query->where('investments.created_at', '>=', $periodFrom);
        }
        if (isset($periodTo)) {
            $query->where('investments.created_at', '<=', $periodTo);
        }
        $query->whereNull('investments.cancelled_at');
        $investments = $query->get()
            ->map(static function (Investment $investment) {
                return [
                    'amount' => $investment->amount,
                    'is_first_investment' => $investment->is_first_investment,
                    'project_name' => $investment->project->name,
                    'created_at' => $investment->created_at,
                    'investment_type' => $investment->is_first_investment ? 'first' : 'subsequent',
                    'investor' => $investment->investor->details->display_name,
                    'provision_type' => $investment->type,
                    ];
            })->all();

        return [
            'investments' => $investments, 
            'draw' => $request->draw,
        ];
    }
}
