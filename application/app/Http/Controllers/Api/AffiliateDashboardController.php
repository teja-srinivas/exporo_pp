<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\LinkClick;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AffiliateDashboardController extends Controller
{
    /**
     * @param  Request  $request
     * @return Array
     * @throws ValidationException
     */
    public function getClicks(Request $request)
    {
        $this->authorize('management.affiliate.dashboard.view');

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
                    $periodFrom = isset($request->first) ? Carbon::create($request->first)->startOfDay() : null;
                    $periodTo = isset($request->second) ? Carbon::create($request->second)->endOfDay() : null;

                    break;
                default:
                    $periodFrom = Carbon::now()->subDays(30);
            }
        }

        $clicksQuery = $user->linkClicks();

        if (isset($periodFrom)) {
            $clicksQuery->where('link_clicks.created_at', '>=', $periodFrom);
        }

        if (isset($periodTo)) {
            $clicksQuery->where('link_clicks.created_at', '<=', $periodTo);
        }

        $clicks = $clicksQuery->get()
            ->map(static function (LinkClick $click) {

                $investment = $click->investment;

                if ($investment !== null) {
                    switch ($click->investment->project->type) {
                        case "Exporo Financing":
                            $type = "Exporo Finanzierung";

                            break;
                        case "Exporo Bestand":
                            $type = "Exporo Bestand";

                            break;
                        default:
                            $type = null;
                    }
                }

                return [
                    'created_at' => $click->created_at,
                    'project_type' => $type ?? "null",
                    'link_title' => $click->link->link->title,
                    'affiliate_type' => $click->link->getType(),
                    'device' => $click->device,
                    'country' => $click->country,
                    'investment_type' => $investment !== null ?
                        ($investment->is_first_investment ? "first" : "subsequent") :
                    'null',
                    'investor_id' => $investment !== null ? $investment->investor_id : "null",
                    'investment_id' => $investment !== null ? $investment->id : "null",
                ];
            })->all();

        return [
            'clicks' => $clicks,
            'draw' => $request->draw,
        ];
    }
}
