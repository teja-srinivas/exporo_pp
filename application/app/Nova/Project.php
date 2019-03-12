<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use AwesomeNova\Cards\FilterCard;
use App\Nova\Filters\IsApprovedProject;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Project::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'description'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->withCount('investments');
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('Financing Entity ID')->sortable(),
            Number::make('Immo Project ID')->sortable(),
            Text::make('Description'),
            Text::make('Legal Setup')->sortable(),
            Text::make('Schema', 'schema.name')->sortable(),
            Text::make('Status')->sortable(),
            Date::make('Funding Start', 'launched_at')->sortable(),
            Date::make('Updated At')->sortable(),

            File::make('Image')
                ->disableDownload()
                ->hideWhenUpdating()
                ->deletable(false)
                ->preview(function () {
                    return empty($this->image) ? null : 'https://cdn.exporo.de/image-cache/320/' . $this->image;
                }),

            Number::make('Investments', 'investments_count')
                ->onlyOnIndex()
                ->sortable(),
            Text::make('Legal Setup')->hideFromIndex(),
            Number::make('Interest Rate')->hideFromIndex()->step(0.01),
            Number::make('Margin')->hideFromIndex()->step(0.01),
            Number::make('Capital Cost')->hideFromIndex(),
            Date::make('Payback Min At')->hideFromIndex(),
            Date::make('Payback Max At')->hideFromIndex(),
            Number::make('Runtime')->hideFromIndex(),

            HasMany::make('Investments'),
        ];
    }

    public function title()
    {
        return parent::title() . ' (' . $this->id . ')';
    }


    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            new FilterCard(new IsApprovedProject()),
        ];
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
            new IsApprovedProject(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
