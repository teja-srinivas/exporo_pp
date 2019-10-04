<?php

declare(strict_types=1);

namespace App\Traits;

use App\LinkInstance;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read LinkInstance $userInstance
 *
 * @mixin Model
 */
trait HasLinkInstance
{
    /**
     * Returns or creates a new link instance for the currently authorized user.
     *
     * @return MorphOne
     */
    public function userInstance(): MorphOne
    {
        $user = auth()->user();

        if ($user === null) {
            throw new InvalidArgumentException('Cannot grab link instance during unauthorized access');
        }

        return $this->morphOne(LinkInstance::class, 'link')
            ->where('user_id', $user->getAuthIdentifier())
            ->withDefault(static function (LinkInstance $instance) use ($user) {
                $instance->user()->associate($user);
            });
    }
}
