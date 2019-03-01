<?php

namespace App\Http\Helper\Request;

use Illuminate\Http\Request;

/**
 * Parses Request fields based on the format described in the JsonApi spec:
 * http://jsonapi.org/format/#fetching-sorting
 */
class FieldParser
{
    /** @var Field[] */
    protected $fields;

    protected function __construct(array $filters, array $sorts)
    {
        $this->fields = $this->parseFields(
            $filters, $this->parseSorts($sorts)
        );
    }

    /**
     * Checks if the given field has additional data.
     *
     * @param string $field
     * @return bool
     */
    public function has(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    /**
     * Gets the parsed entry for the given field (if any)
     *
     * If any default filter or sort value has been provided,
     * this will create a temporary instance of that field
     * and return that one instead (even if it doesn't exist).
     *
     * @param string $field
     * @param string|null $filter
     * @param string|null $sort
     * @return Field|null
     */
    public function get(string $field, string $filter = null, string $sort = null): ?Field
    {
        $field = $this->fields[$field] ?? null;

        if ($field !== null) {
            return $field;
        }

        if ($filter === null && $sort === null) {
            return null;
        }

        return new Field($filter ?? '', $sort ?? '');
    }

    /**
     * Checks if the given fields wants to be filtered.
     *
     * @param string $field
     * @return bool
     */
    public function filters(string $field): bool
    {
        if (!$this->has($field)) {
            return false;
        }

        return !empty($this->get($field)->filter);
    }

    protected function parseSorts(array $sorts)
    {
        // Sort works via column names, descending marked via "-" prefix
        return collect(array_filter($sorts))->mapWithKeys(function ($column) {
            $descending = strpos($column, '-') === 0;
            $name = $descending ? substr($column, 1) : $column;

            return [
                $name => $descending ? 'desc' : 'asc',
            ];
        })->all();
    }

    protected function parseFields(array $filters, array $sort)
    {
        $fields = array_unique(array_merge(
            array_keys($filters),
            array_keys($sort)
        ));

        return collect($fields)->mapWithKeys(function ($column) use ($filters, $sort) {
            return [
                $column => new Field(
                    $filters[$column] ?? '',
                    $sort[$column] ?? ''
                ),
            ];
        });
    }

    /**
     * Parses the fields in the given Laravel request.
     * This uses the "filter" and "sort" parameters.
     *
     * @param Request $request
     * @return FieldParser
     */
    public static function fromRequest(Request $request)
    {
        return new FieldParser(
            $request->get('filter', []),
            explode(',', $request->get('sort', ''))
        );
    }
}
