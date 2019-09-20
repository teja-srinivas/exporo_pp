<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\SendMail;
use App\Jobs\SendAcceptMail;
use App\Jobs\SendRejectMail;
use Illuminate\Support\Facades\Bus;

class SendUserAcceptOrRejectMailOnUpdateTest extends TestCase
{
    /** @test */
    public function it_sends_rejection_mails_when_users_get_declined()
    {
        $this->expectsJobs(SendRejectMail::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $user->update(['rejected_at' => now()]);
    }

    /** @test */
    public function it_sends_accept_mails_when_users_get_accepted()
    {
        $this->expectsJobs(SendAcceptMail::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $user->update(['accepted_at' => now()]);
    }

    /** @test */
    public function it_does_not_send_mails_on_unrelated_updates()
    {
        $this->doesntExpectJobs([
            SendAcceptMail::class,
            SendRejectMail::class,
        ]);

        /** @var User $user */
        $user = factory(User::class)->state('accepted')->create();
        $user->update(['accepted_at' => null]);

        /** @var User $user */
        $user = factory(User::class)->state('rejected')->create();
        $user->update(['rejected_at' => null]);
    }

    /** @test */
    public function it_only_sends_rejection_mails_to_validated_users()
    {
        $fake = Bus::fake([SendMail::class]);

        /** @var User $user */
        $user = factory(User::class)->create();
        $user->update(['rejected_at' => now()]);

        $fake->assertNotDispatched(SendMail::class);
    }
}
