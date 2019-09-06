<?php

declare(strict_types=1);

namespace App\Http\ViewComposers\LinkDashboard;

use App\Services\DeviceIdentification;
use Illuminate\Database\Eloquent\Builder;

class ByDevice extends Graph
{
    public function getName(): string
    {
        return 'device';
    }

    public function getTitle(): string
    {
        return 'Gerätetyp';
    }

    public function getLabel(string $group): string
    {
        static $labels = [
            DeviceIdentification::DESKTOP => 'Desktop',
            DeviceIdentification::TABLET => 'Tablet',
            DeviceIdentification::PHONE => 'Smartphone',
        ];

        return $labels[$group] ?? ucfirst($group);
    }

    public function plot(): Builder
    {
        return $this->buildQuery('device');
    }
}
