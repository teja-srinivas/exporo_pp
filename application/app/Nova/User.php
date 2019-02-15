<?php

namespace App\Nova;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = UserModel::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'email',
    ];

    public static $with = [
        'details',
    ];

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

            Date::make('Created At')->sortable(),
            Date::make('Email Verified At')->onlyOnDetail(),
            Date::make('Accepted At')->onlyOnDetail(),
            Date::make('Declined At')->onlyOnDetail(),

            BelongsTo::make('Parent User', 'parent', User::class)->searchable(),

            HasMany::make('Child User', 'children', User::class),

            Text::make('Vorname', 'first_name')
                ->rules('required', 'max:255'),

            Text::make('Nachname', 'last_name')
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->hideFromIndex(),

            Heading::make('Kontakt'),

            Select::make('Anrede', 'details.salutation')
                ->onlyOnForms()
                ->options([
                    'male' => 'Herr',
                    'female' => 'Frau',
                ]),

            Select::make('Titel', 'details.title')
                ->onlyOnForms()
                ->options(UserModel::TITLES),

            Text::make('Firma', 'details.company'),

            Heading::make('Herkunft'),

            Select::make('Mehrwertsteuer', 'details.vat_included')
                ->onlyOnForms()
                ->options([
                    null => 'keine',
                    true => 'Inkludiert',
                    false => 'On Top',
                ]),

            Heading::make('Finanzen'),

            HasMany::make('Bills'),

            HasMany::make('Investor', 'investors', Investor::class),

            HasMany::make('Investments'),

            BelongsToMany::make('AGBs', 'agbs')
                ->fields(function () {
                    return [
                        Date::make('Akzeptiert', 'created_at'),
                    ];
                }),

            // MorphToMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),
        ];
    }

    public function title()
    {
        return $this->details->display_name . ' (' . $this->id . ')';
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
            new Metrics\NewUsers,
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
