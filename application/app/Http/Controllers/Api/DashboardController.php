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
            'period' => ['nullable', 'in:this_month,last_month,default'],
        ]);

        if (isset($data['period'])) {
            switch ($data['period']) {
                case 'this_month':
                    $period = Carbon::now()->startOfMonth();
                    break;
                case 'last_month':
                    $period = Carbon::now()->startOfMonth()->subMonth();
                    break;
                default:
                    $period = Carbon::now()->subDays(30);
            }
        }

        $showAll = isset($period) ? false : true;

        $query = $user->investments();
        if ($showAll === false) {
            $query->where('investments.created_at', '>=', $period);
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

        return $investments;
    }
}
