<?php

declare(strict_types=1);

namespace App\Http\ViewComposers\LinkDashboard;

use App\Models\Link;
use App\Models\BannerLink;
use Illuminate\Database\Eloquent\Builder;

class ByType extends Graph
{
    public function getName(): string
    {
        return 'type';
    }

    public function getTitle(): string
    {
        return 'Typ';
    }

    public function getLabel(string $group): string
    {
        static $labels = [
            BannerLink::MORPH_NAME => 'Banner',
            Link::MORPH_NAME => 'Links',
        ];

        return $labels[$group] ?? $group;
    }

    public function plot(): Builder
    {
        return self::buildQuery('link_type');
    }
}
