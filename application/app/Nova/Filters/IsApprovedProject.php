<?php

declare(strict_types=1);

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Illuminate\Database\Eloquent\Builder;

class IsApprovedProject extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $notApproved = is_array($value) ? ($value['not-approved'] ?? false) : $value === 'not-approved';

        return $query->when($notApproved, static function (Builder $query) {
            $query->whereNull('approved_at');
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Alle Projekte' => 'all',
            'Nicht freigegeben' => 'not-approved',
        ];
    }
}
