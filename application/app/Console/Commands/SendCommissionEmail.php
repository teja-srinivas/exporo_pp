<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Jobs\SendMail;

class SendCommissionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:commissionEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends commission email for created bills';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->getReleasedBills() as $bill) {
            $date = now()->subMonth(1);

            SendMail::dispatch([
                'Provision' => format_money($bill->getTotalNet()),
                'Link' => 'p.exporo.com',
                'billing_month' => $date->format('F'),
                'billing_year' => $date->format('Y'),
            ], $bill->user, config('mail.templateIds.commissionCreated'))->onQueue('emails');
        };
    }

    private function getReleasedBills()
    {
        return Bill::where('released_at', now()->subDay()->format('Y-m-d'))->get();
    }
}
