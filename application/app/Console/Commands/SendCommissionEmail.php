<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Jobs\SendMail;
use Carbon\Carbon;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bills = $this->getReleasedBills();
        foreach ($bills as $bill) {
            SendMail::dispatch([
                'Provision' => format_money($bill->getTotalNet()),
                'Link' => 'p.exporo.com',
                'billing_month' => Carbon::now()->format('m'),
                'billing_year' => Carbon::now()->format('y'),
            ], $bill->user, config('mail.templateIds.commissionCreated'))->onQueue('emails');
        };
    }

    private function getReleasedBills()
    {
        return (Bill::where('released_at', now()->subDay(1)->format('Y-m-d'))->get());
    }
}
