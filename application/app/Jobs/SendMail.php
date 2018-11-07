<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $templateData;
    protected $templateId;

    /** @var User */
    protected $user;

    /** @var string */
    protected $mail;

    public function __construct(array $templateData, $user, $templateId)
    {
        $this->user = $user;
        $this->templateData = array_merge($templateData, [
            'Anrede' => implode(' ', [$this->user->details->salutation === "male" ? 'Herr' : 'Frau', $this->user->details->title]),
            'Vorname' => $this->user->first_name,
            'Nachname' => $this->user->last_name,
        ]);
        $this->templateId = $templateId;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        if (env('APP_ENV') !== 'prod') {
            $this->mail = 'stageapp@exporo.com';
        } else {
            $this->mail = $this->user->email;
        }

        $mailer->raw(null, function (Message $message) {
            $message->to($this->mail);
            $message->from('partnerprogramm@exporo.com');

            $data = [
                'personalizations' => [
                    [
                        'dynamic_template_data' => $this->templateData
                    ],
                ],
                'template_id' => $this->templateId,
            ];
            $message->embedData(
                config('mail.driver') === 'sendgrid' ? $data : json_encode($data),
                SendgridTransport::SMTP_API_NAME
            );
        });
    }
}
