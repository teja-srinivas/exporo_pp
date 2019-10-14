<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\VatId;
use App\Rules\PhoneNumber;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
        return $this->user()->can('update', $this->route('user')) || $this->user()->can('manage', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var User $user */
        $user = $this->route('user');

        return array_merge(
            self::getUserValidationRules($user),
            self::getDetailValidationRules(false, $user),
            $this->user()->can('manage', $user) ? [
                'roles' => ['sometimes'],
                'permissions' => ['sometimes'],
            ] : []
        );
    }

    // Make sure we add our custom birth_date field to both,
    // the validation and validated data array

    public function validationData()
    {
        return $this->addCustomData(parent::validationData());
    }

    public function validated()
    {
        $data = $this->addCustomData(parent::validated());

        if (empty($this->route('user')->api_token)) {
            $data['api_token'] = Str::random(64);
        }

        return $data;
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
            'first_name' => $prefix.'string|max:100',
            'last_name' => $prefix.'string|max:100',
            'email' => $prefix.'string|email|max:255|unique:users,email,'.optional($updating)->id,
        ];

        // Only allow acceptance status if it's a user already
        $guard = auth();

        if ($guard->check() && $guard->user()->can('process', $updating)) {
            $rules['accept'] = 'nullable|boolean';
        }

        return $rules;
    }

    /**
     * Additional rules for the user details.
     *
     * @param bool $required
     * @param User|null $updating
     * @return array
     */
    public static function getDetailValidationRules($required = true, User $updating = null)
    {
        $prefix = $required ? 'required' : 'nullable';
        $datePrefix = $required ? 'required' : 'required_with:birth_day,birth_month,birth_year|nullable';

        $adultYear = now()->subYears(18);

        return [
            'company' => 'nullable|string|max:100',
            'display_name' => 'nullable|string',
            'title' => ['nullable', Rule::in(User::TITLES)],
            'salutation' => "{$prefix}|in:male,female",
            'birth_day' => "{$prefix}|numeric|min:1|max:31",
            'birth_month' => "{$prefix}|numeric|min:1|max:12",
            'birth_year' => "{$prefix}|numeric|min:".now()->subYears(120)->year.'|max:'.$adultYear->year,
            'birth_date' => "{$datePrefix}|date|before_or_equal:".$adultYear, // needs to be an adult
            'birth_place' => 'nullable|string|max:100',
            'address_street' => "{$prefix}|string|max:100",
            'address_number' => "{$prefix}|string|max:20",
            'address_addition' => 'nullable|string|max:100',
            'address_zipcode' => "{$prefix}|string|max:20",
            'address_city' => 'nullable|string|max:100',
            'phone' => [$prefix, 'string', new PhoneNumber],
            'website' => 'nullable|string|max:100',
            'vat_id' => ['nullable', app(VatId::class)],
            'tax_office' => 'nullable|string|max:100',
            'iban' => 'nullable|iban',
            'bic' =>'nullable|bic',
        ] + ($updating !== null ? [
            'vat_included' => 'boolean',
            'vat_amount' => 'numeric',
        ] : []);
    }
}
