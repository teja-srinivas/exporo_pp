<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use App\Models\Contract;

trait TestsControllers
{
    protected function createActiveUserWithPermission(string ...$permissions): User
    {
        /** @var User $user */
        $user = factory(User::class)->state('accepted')->create();

        /** @var Contract $contract */
        $contract = factory(Contract::class)->state('active')->make();
        $contract->user()->associate($user);
        $contract->save();

        $user->givePermissionTo($permissions);

        return $user;
    }
}
