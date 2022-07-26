<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\CommissionBonus;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommissionBonus extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', CommissionBonus::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
