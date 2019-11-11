<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\ProductContract;

class TerminateOldContractOnApprovalTest extends TestCase
{
    /** @test */
    public function it_terminates_the_previous_contract_on_approval(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var ProductContract $old */
        $old = factory(ProductContract::class)->state('active')->create([
            'user_id' => $user->getKey(),
            'accepted_at' => '2019-10-01',
            'released_at' => '2019-10-01',
        ]);

        /** @var ProductContract $previous */
        $previous = factory(ProductContract::class)->state('active')->create([
            'user_id' => $user->getKey(),
            'accepted_at' => '2019-11-01',
            'released_at' => '2019-11-01',
        ]);

        /** @var ProductContract $new */
        $new = factory(ProductContract::class)->create([
            'released_at' => now(),
            'user_id' => $user->getKey(),
        ]);

        Carbon::setTestNow('1.2.2019 01:23:45');

        $new->accepted_at = now();
        $new->save();

        $old->refresh();
        $this->assertEquals(null, $old->terminated_at);

        $previous->refresh();
        $this->assertEquals($new->accepted_at, $previous->terminated_at);
    }
}
