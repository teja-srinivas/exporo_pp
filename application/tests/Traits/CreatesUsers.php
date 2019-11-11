<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\User;
use App\Models\Contract;
use App\Models\ProductContract;
use App\Models\PartnerContract;

trait CreatesUsers
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

        /** @var ProductContract $productContract */
        $productContract = factory(ProductContract::class)->state('active')->make();
        $productContract->user()->associate($user);
        $productContract->save();

        /** @var PartnerContract $partnerContract */
        $partnerContract = factory(PartnerContract::class)->state('active')->make();
        $partnerContract->user()->associate($user);
        $partnerContract->save();

        return $user;
    }
}
