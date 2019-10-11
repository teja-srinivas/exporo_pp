<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Encryptable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Custom Audit implementation to support localizable Dates.
 */
class Audit extends \OwenIt\Auditing\Models\Audit
{
    /**
     * Returns a collection of all modifications that happened to the original model.
     * This already decrypts all values (if possible) and filters out possible duplicates.
     *
     * @return Collection
     */
    public function getFilteredModifications(): Collection
    {
        $type = Relation::getMorphedModel($this->auditable_type) ?? $this->auditable_type;

        /** @var Model $model */
        $model = new $type;

        return collect($this->getModified())
            // Remove all backend only fields
            ->diffKeys(array_flip($model->getHidden()))

            // Decrypt all possible values
            ->map(function (array $entry) {
                return array_map(function ($value) {
                    return is_string($value) ? Encryptable::decrypt($value) : $value;
                }, $entry);
            })

            // Filter out those that are the same, because we encrypted them beforehand
            // and thus did not know that they're different
            ->reject(function (array $entry) {
                if (! is_array($entry)) {
                    return false;
                }

                if (($count = count($entry)) < 2) {
                    return false;
                }

                return count(array_unique(array_values($entry), SORT_REGULAR)) < $count;
            });
    }

    /**
     * Get the formatted value of an Eloquent model.
     *
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function getFormattedValue(Model $model, string $key, $value)
    {
        // Apply defined get mutator
        if ($model->hasGetMutator($key)) {
            return $model->mutateAttribute($key, $value);
        }

        // Cast to native PHP type
        if ($model->hasCast($key)) {
            return $model->castAttribute($key, $value);
        }

        // Honour DateTime attribute
        if ($value !== null && in_array($key, $model->getDates(), true)) {
            return is_array($value) ? Carbon::make($value) : $model->asDateTime($value);
        }

        return $value;
    }
}
