<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;

class StoreCampaign extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manage', Campaign::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'all_users' => 'required|boolean',
            'is_blacklist' => 'required|boolean',
            'started_at' => 'date|nullable',
            'ended_at' => 'date|nullable',
            'image' => 'mimes:jpeg,gif,png|nullable',
            'document' => 'mimes:pdf|nullable',
            'selection' => 'required|string',
            'url' => 'string|nullable',
        ];
    }
}
