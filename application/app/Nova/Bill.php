<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Bill extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bill::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'userDetails',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->withCount('commissions');
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Date::make('Released At')->sortable(),
            Date::make('Pdf Created At')->sortable(),
            Date::make('Mail Sent At')->sortable(),

            Text::make('Name', function () {
                return $this->resource->getDisplayName();
            })->onlyOnIndex(),

            Number::make('Commissions', 'commissions_count')
                ->onlyOnIndex()
                ->sortable(),

            Currency::make('Amount', function () {
                return $this->resource->getTotalGross();
            }),

            BelongsTo::make('Partner', 'user', User::class)->searchable(),
            HasMany::make('Provisionen', 'commissions', Commission::class),
        ];
    }

    public function title()
    {
        /** @var \App\Models\Bill $bill */
        $bill = $this->resource;
        return '#' . $bill->user_id . ' ' . $bill->userDetails->display_name . ': ' . $bill->getDisplayName();
    }


    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
