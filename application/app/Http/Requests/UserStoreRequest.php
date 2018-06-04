<?php

namespace App\Http\Requests;

use App\User;
use App\UserDetails;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            User::getValidationRules($this->route('user')),
            UserDetails::getValidationRules(false)
        );
    }

    // Make sure we add our custom birth_date field to both,
    // the validation and validated data array

    protected function validationData()
    {
        return $this->addCustomData(parent::validationData());
    }

    public function validated()
    {
        return $this->addCustomData(parent::validated());
    }

    protected function addCustomData($array)
    {
        $array['birth_date'] = self::makeBirthDate($array);

        return $array;
    }

    /**
     * Builds the birth date from a form input array.
     * Returns null if the date is invalid.
     *
     * @param array $data
     * @return string|null
     */
    public static function makeBirthDate(array $data)
    {
        $date = sprintf(
            '%s-%s-%s',
            $data['birth_year'] ?? '',
            $data['birth_month'] ?? '',
            $data['birth_day'] ?? ''
        );

        return $date !== '--' ? $date : null;
    }
}
