<?php

declare(strict_types=1);

namespace App\Nova;

use App\Models\Document as DocumentModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Document extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = DocumentModel::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'user_id', 'name', 'filename',
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

            BelongsTo::make('User')->searchable(),

            Text::make('Name', 'name')
                ->rules('required', 'max:255'),
        ];
    }
}
