<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Jobs\SendMail;

class SendBillMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:mail';

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
        /** @var Bill $bill */
        foreach ($this->getReleasedBills() as $bill) {
            $date = $bill->getBillingMonth();

            SendMail::dispatch([
                'Provision' => format_money($bill->net),
                'Link' => 'p.exporo.com',
                'billing_month' => $date->format('F'),
                'billing_year' => $date->format('Y'),
            ], $bill->user, config('mail.templateIds.commissionCreated'))->onQueue('emails');
        }
    }

    private function getReleasedBills()
    {
        return Bill::query()
            ->whereDate('released_at', now())
            ->where('pdf_created', true)
            ->join('commissions', 'bills.id', 'commissions.bill_id')
            ->groupBy('bills.id')
            ->select('bills.*')
            ->selectRaw('sum(net) as net')
            ->get();
    }
}
