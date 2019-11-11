<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Contract;
use Illuminate\Queue\SerializesModels;

class ContractUpdated
{
    use SerializesModels;

    /** @var Contract */
    public $contract;

    /**
     * @param Contract $user
     */
    public function __construct(Contract $user)
    {
        $this->contract = $user;
    }
}
