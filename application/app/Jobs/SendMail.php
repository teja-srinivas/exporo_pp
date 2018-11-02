<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $templateData;
    protected $templateId;
    protected $user;
    protected $mail;


    public function __construct(array $templateData, $user, $templateId)
    {
        $this->templateData = $templateData;
        $this->user = $user;
        $this->templateId = $templateId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (env('APP_ENV') !== 'production') {
            $this->mail = 'a.vertgewall@exporo.com';
        } else {
            $this->mail = $this->user->email;
        }

        Mail::send([], [], function (Message $message) {
            $message
                ->to($this->mail)
                ->from('partnerprogramm@exporo.com')
                ->embedData([
                    'personalizations' => [
                        [
                            'dynamic_template_data' =>
                                $this->templateData
                        ],
                    ],
                    'template_id' => $this->templateId,
                ], SendgridTransport::SMTP_API_NAME);
        });
    }
}
