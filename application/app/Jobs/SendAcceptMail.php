<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAcceptMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
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
        ], $this->user, config('mail.templateIds.approved'))->onQueue('emails');
    }
}
