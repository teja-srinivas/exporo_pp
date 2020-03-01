<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        if (!$request->user()->can('management.dashboard.view')) {
            return redirect()->route('accounting');
        }

        $user = $request->user();

        $count = $user->investments()->count();

        $campaigns = Campaign::query()
            ->where('is_active', 1)
            ->whereNotNull('image_url')
            ->where(static function (Builder $query) {
                $query->where('started_at', '<=', now());
                $query->orWhereNull('started_at');
            })
            ->where(static function (Builder $query) {
                $query->where('ended_at', '>=', now());
                $query->orWhereNull('ended_at');
            })->get()->map(static function (Campaign $campaign) use ($user) {
                $users = $campaign->users->map(static function ($campaignUser) {
                    return $campaignUser->id;
                })->all();

                if (
                    !$campaign->all_users
                    && (
                        (($campaign->is_blacklist && in_array($user->id, $users))
                        || (!$campaign->is_blacklist && !in_array($user->id, $users)))
                    )
                ) {
                    return [
                        'ignore' => true,
                    ];
                }

                return [
                    'title' => $campaign->title,
                    'image_url' => $campaign->image_url,
                    'url' => $campaign->url,
                    'ignore' => false,
                ];
            });

        $filtered = $campaigns->filter(static function ($value) {
            return !$value['ignore'];
        });

        return response()->view('dashboard.index', [
            'investmentCount' => $count,
            'campaigns' => $filtered,
        ]);
    }
}
