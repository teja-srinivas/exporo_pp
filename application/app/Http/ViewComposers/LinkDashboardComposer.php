<?php

namespace App\Http\ViewComposers;

use stdClass;
use App\LinkClick;
use App\LinkInstance;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class LinkDashboardComposer
{
    public function compose(View $view)
    {
        $view->with('series', $this->getClicksForUser(Auth::user()));
    }

    private function getClicksForUser(Authenticatable $user): array
    {
        // Get all clicks per day and device type for the given user
        return $this->convertToHighcharts(LinkClick::query()
            ->whereIn('instance_id', LinkInstance::query()
                ->where('user_id', $user->getAuthIdentifier())
                ->select('id'))
            ->selectRaw('unix_timestamp(date(link_clicks.created_at)) * 1000 as day')
            ->selectRaw('count(link_clicks.id) as clicks')
            ->addSelect('device')
            ->groupBy('day', 'device')
            ->toBase()
            ->get());
    }

    private function convertToHighcharts(Collection $results): array
    {
        return $results->mapToGroups(function (stdClass $record) {
            return [
                    $record->device => [$record->day, $record->clicks],
                ];
        })
            ->map(function (Collection $group, string $name) {
                return [
                    'name' => $name,
                    'data' => $group,
                ];
            })
            ->values()
            ->toArray();
    }
}
