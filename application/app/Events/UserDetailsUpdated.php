<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\UserDetails;
use Illuminate\Queue\SerializesModels;

class UserDetailsUpdated
{
    use SerializesModels;

    /** @var UserDetails */
    public $details;

    /**
     * Create a new event instance.
     *
     * @param UserDetails $details
     */
    public function __construct(UserDetails $details)
    {
        $this->details = $details;
    }
}
