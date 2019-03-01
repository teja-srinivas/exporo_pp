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

    /** @var array */
    protected $data;

    /** @var string */
    protected $mail;

    public function __construct(array $templateData, User $user, string $templateId)
    {
        if (app()->environment() !== 'prod') {
            $this->mail = 'stageapp@exporo.com';
        } else {
            $this->mail = $user->email;
        }

        $this->data = [
            'personalizations' => [
                [
                    'dynamic_template_data' => array_merge($templateData, [
                        'Anrede' => implode(' ', [
                            $user->details->salutation === "male" ? 'Herr' : 'Frau',
                            $user->details->title
                        ]),
                        'AnredeLang' => $user->getGreeting(),
                        'Vorname' => $user->first_name,
                        'Nachname' => $user->last_name,
                    ]),
                ],
            ],
            'template_id' => $templateId,
        ];
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->raw(null, function (Message $message) {
            $message->to($this->mail);
            $message->from('info@partner.exporo.de');
            $message->embedData(
                config('mail.driver') === 'sendgrid' ? $this->data : json_encode($this->data),
                SendgridTransport::SMTP_API_NAME
            );
        });
    }
}
