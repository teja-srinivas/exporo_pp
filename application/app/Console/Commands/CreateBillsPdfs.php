<?php

namespace App\Console\Commands;

use App\Jobs\CreateBillPdfJob;
use App\Models\Bill;
use Illuminate\Console\Command;

class CreateBillsPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:pdf {--live}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the bill PDF files';


    public function handle()
    {
        $bills = $this->getReleasedBills();
        $live = $this->hasOption('live');

        foreach ($bills as $bill) {
            CreateBillPdfJob::dispatch($bill, $live)->onQueue('createBillPdf');
        }
    }

    private function getReleasedBills()
    {
        return Bill::query()
            ->whereDate('released_at', now())
            ->where('pdf_created', false)
            ->get();
    }
}
