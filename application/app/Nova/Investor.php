<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Investor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Investor::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'user.details',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->withCount('investments');
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
            Text::make('Vorname', 'first_name'),
            Text::make('Nachname', 'last_name'),
            Date::make('Aktiviert', 'activation_at')->sortable(),
            Number::make('Investments', 'investments_count')->sortable(),

            BelongsTo::make('Partner', 'user', User::class)
                ->searchable()
                ->nullable(),

            HasMany::make('Investments'),
        ];
    }

    public function title()
    {
        return $this->first_name . ' ' . $this->last_name . ' (' . $this->id . ')';
    }

    public function subtitle()
    {
        $name = optional($this->user)->getDisplayName();

        if ($name === null) {
            return null;
        }

        return "Partner: $name";
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
        return [
            new Filters\HasPartner,
        ];
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
