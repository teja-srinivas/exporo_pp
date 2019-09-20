<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\User;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\SendMail;
use Tests\TestsControllers;
use App\Policies\UserPolicy;

class VerificationControllerTest extends TestCase
{
    use TestsControllers;

    /** @test */
    public function it_sends_verification_mails()
    {
        $this->expectsJobs(SendMail::class);

        $user = $this->createActiveUserWithPermission(UserPolicy::PROCESS_PERMISSION);

        $this->be($user);

        $this->post(route('users.verification.store', $user));
    }

    /** @test */
    public function only_users_with_proces_permission_have_access()
    {
        $user = $this->createActiveUser();

        $this->be($user);

        $response = $this->post(route('users.verification.store', $user));

        $response->assertForbidden();
    }
}
