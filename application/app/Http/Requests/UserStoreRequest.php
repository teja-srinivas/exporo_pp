<?php

namespace App\Http\Requests;

use App\Rules\VatId;
use App\User;
use App\UserDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            self::getUserValidationRules($this->route('user')),
            self::getDetailValidationRules(false)
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
        $birthDate = self::makeBirthDate($array);

        if ($birthDate !== null) {
            $array['birth_date'] = $birthDate;
        }

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

    /**
     * General user validation rules.
     *
     * @param User|null $updating
     * @return array
     */
    public static function getUserValidationRules(User $updating = null)
    {
        $prefix = $updating === null ? 'required|' : '';

        $rules = [
            'first_name' => $prefix . 'string|max:100',
            'last_name' => $prefix . 'string|max:100',
            'email' => $prefix . 'string|email|max:255|unique:users,email,' . optional($updating)->id,
        ];

        // Only allow acceptance status if it's a user already
        if (auth()->check() && auth()->user()->can('process', $updating)) {
            $rules['accept'] = 'nullable|boolean';
        }

        return $rules;
    }

    /**
     * Additional rules for the user details.
     *
     * @param bool $required
     * @return array
     */
    public static function getDetailValidationRules($required = true)
    {
        $prefix = $required ? 'required' : 'nullable';
        $datePrefix = $required ? 'required' : 'required_with:birth_day,birth_month,birth_year|nullable';

        $adultYear = now()->subYears(18);

        return [
            'company' => 'nullable|string|max:100',
            'title' => ['nullable', Rule::in(User::TITLES)],
            'salutation' => "{$prefix}|in:male,female",
            "birth_day" => "{$prefix}|numeric|min:1|max:31",
            "birth_month" => "{$prefix}|numeric|min:1|max:12",
            "birth_year" => "{$prefix}|numeric|min:". now()->subYears(120)->year . '|max:' . $adultYear->year,
            'birth_date' => "{$datePrefix}|date|before_or_equal:" . $adultYear, // needs to be an adult
            'birth_place' => "{$prefix}|string|max:100",
            'address_street' => 'nullable|string|max:100',
            'address_number' => 'nullable|string|max:20',
            'address_addition' => 'nullable|string|max:100',
            'address_zipcode' => 'nullable|string|max:20',
            'address_city' => 'nullable|string|max:100',
            'phone' => "{$prefix}|string|max:100",
            'website' => 'nullable|string|max:100',
            'vat_id' => ['nullable', new VatId()],
            'tax_office' => 'nullable|string|max:100',
        ];
    }
}
