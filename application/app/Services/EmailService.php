<?php
declare(strict_types=1);


namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

final class EmailService
{
    protected $templateData;
    protected $templateId;
    protected $user;
    protected $mail;
    public function SendMail(array $templateData, $user, $templateId)
    {
        $this->templateId = $templateId;
        $this->templateData = $templateData;
        $this->user = $user;
        if(env('APP_ENV') !== 'production'){
            $this->mail = 'a.vertgewall@exporo.com';
        }
        else{
            $this->mail = $user->email;
        }
        Mail::send([], [], function (Message $message) {
            $message
                ->to('a.vertgewall@exporo.com')
                ->from('partnerprogramm@exporo.com')
                ->embedData([
                    'personalizations' => [
                        [
                            'dynamic_template_data' => $this->templateData
                            ],
                        ],
                    'template_id' => $this->templateId,
                ], SendgridTransport::SMTP_API_NAME);
        });
    }
}
