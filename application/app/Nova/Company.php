<?php

declare(strict_types=1);

namespace App\Nova;

use App\Models\Company as CompanyModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Company extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CompanyModel::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name', 'name')
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:companies,email')
                ->updateRules('unique:companies,email,{{resourceId}}'),

            Text::make('Street', 'street')
                ->rules('required'),

            Text::make('Street No.', 'street_no')
                ->rules('required'),

            Text::make('Postal Code', 'postal_code')
                ->rules('required'),

            Text::make('City', 'city')
                ->rules('required'),

            Text::make('Phone', 'phone_number')
                ->rules('required'),

            Text::make('Fax', 'fax_number')
                ->rules('required'),

            Text::make('Website', 'website')
                ->rules('required'),

        ];
    }
}
