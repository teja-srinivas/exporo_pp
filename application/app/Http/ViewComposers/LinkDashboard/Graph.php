<?php

declare(strict_types=1);

namespace App\Http\ViewComposers\LinkDashboard;

use App\LinkClick;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

abstract class Graph
{
    public const GROUP_NAME = 'name';

    abstract public function getName(): string;

    abstract public function getTitle(): string;

    abstract public function plot(): Builder;

    public function getLabel(string $group): string
    {
        return $group;
    }

    protected function buildQuery(string $group): Builder
    {
        return LinkClick::query()
            ->join('link_instances', 'link_instances.id', 'link_clicks.instance_id')
            ->where('link_instances.user_id', Auth::user()->getAuthIdentifier())
            ->selectRaw('unix_timestamp(date(link_clicks.created_at)) * 1000 as day')
            ->selectRaw('count(link_clicks.id) as clicks')
            ->groupBy('day', $group)
            ->selectRaw(sprintf("%s as %s", $group, self::GROUP_NAME));
    }
}
