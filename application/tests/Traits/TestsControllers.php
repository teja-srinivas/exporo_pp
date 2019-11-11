<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\User;
use App\Models\Contract;
use App\Models\ProductContract;

trait TestsControllers
{
    protected function createActiveUserWithPermission(string ...$permissions): User
    {
        $user = $this->createActiveUser();

        $user->givePermissionTo($permissions);

        return $user;
    }

    protected function createActiveUser(): User
    {
        /** @var User $user */
        $user = factory(User::class)->state('accepted')->create();

        /** @var Contract $contract */
        $contract = factory(ProductContract::class)->state('active')->make();
        $contract->user()->associate($user);
        $contract->save();

        return $user;
    }
}
