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
    protected $signature = 'create:billpdf {--live=}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the bills for released commissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bills = $this->getReleasedBills();
        foreach ($bills as $bill) {
            CreateBillPdfJob::dispatch($bill, $this->option('live') ?? null)->onQueue('createBillPdf');
        }
    }

    private function getReleasedBills()
    {
        return (Bill::where('released_at', now()->format('Y-m-d'))->get());
    }
}
