<?php

namespace App\Console\Commands;

use App\Jobs\SendMail;
use App\Models\Bill;
use App\Repositories\BillRepository;
use Illuminate\Console\Command;

class SendBillMails extends Command
{
    protected $signature = 'bill:mail';
    protected $description = 'Sends commission email for created bills';

    /**
     * Sends "bill released" emails to all users.
     *
     * @param BillRepository $repository
     */
    public function handle(BillRepository $repository)
    {
        $bills = $repository->unsentWithTotals();

        $this->line("Sending mails for {$bills->count()} bill(s)");

        $bills->each(function (Bill $bill) {
            $date = $bill->getBillingMonth();

            SendMail::dispatch([
                'Provision' => format_money($bill->net),
                'Link' => 'p.exporo.com',
                'billing_month' => $date->format('F'),
                'billing_year' => $date->format('Y'),
            ], $bill->user, config('mail.templateIds.commissionCreated'))->onQueue('emails');

            $bill->mail_sent_at = now();
            $bill->save();
        });
    }
}
