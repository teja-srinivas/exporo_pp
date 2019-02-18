<?php

namespace App\Console\Commands;

use App\Jobs\CreateBillPdfJob;
use App\Models\Bill;
use Illuminate\Console\Command;

class CreateBillPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:billpdf {--live}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the bills for released commissions';


    public function handle()
    {
        $bills = $this->getReleasedBills();

        foreach ($bills as $bill) {
            CreateBillPdfJob::dispatch($bill, $this->hasOption('live'))->onQueue('createBillPdf');
        }
    }

    private function getReleasedBills()
    {
        return Bill::query()->whereDate('released_at', now())->get();
    }
}
