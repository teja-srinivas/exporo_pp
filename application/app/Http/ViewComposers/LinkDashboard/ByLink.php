<?php

declare(strict_types=1);

namespace App\Http\ViewComposers\LinkDashboard;

use Illuminate\Database\Eloquent\Builder;

class ByLink extends Graph
{
    public function getName(): string
    {
        return 'link';
    }

    public function getTitle(): string
    {
        return 'Link';
    }

    public function plot(): Builder
    {
        return self::buildQuery('title')
            ->join('links', 'link_instances.link_id', 'links.id');
    }
}
