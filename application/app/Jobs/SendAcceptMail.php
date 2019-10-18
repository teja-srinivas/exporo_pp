<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Auth\Passwords\PasswordBroker;

class SendAcceptMail
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param PasswordBroker $broker
     * @return void
     */
    public function handle(PasswordBroker $broker)
    {
        SendMail::dispatch([
            'Login' => route('password.reset', $broker->createToken($this->user)),
        ], $this->user, 'approved')->onQueue('emails');
    }
}
