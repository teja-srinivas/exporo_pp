<?php

declare(strict_types=1);

namespace App\Http\ViewComposers\LinkDashboard;

use Illuminate\Database\Eloquent\Builder;

class ByCountry extends Graph
{
    public function getName(): string
    {
        return 'country';
    }

    public function getTitle(): string
    {
        return 'Land';
    }

    public function plot(): Builder
    {
        return $this->buildQuery('country');
    }
}
