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
        $bills = $this->getBillsWithoutPDFs();
        $live = $this->hasOption('live');
        
        $this->line("Creating PDFs for {$bills->count()} bill(s)");
        $this->line("Running in live mode: " . ($live ? 'YES' : 'NO'));

        foreach ($bills as $bill) {
            CreateBillPdfJob::dispatch($bill, $live)->onQueue('createBillPdf');
        }
    }

    private function getBillsWithoutPDFs()
    {
        return Bill::query()->where('pdf_created', false)->get();
    }
}
