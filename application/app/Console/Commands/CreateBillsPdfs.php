<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Jobs\CreateBillPdfJob;
use Illuminate\Console\Command;
use App\Repositories\BillRepository;

class CreateBillsPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the bill PDF files';

    public function handle(BillRepository $repository)
    {
        $bills = $repository->withoutPdf();

        $this->line("Creating PDFs for {$bills->count()} bill(s)");

        foreach ($bills as $bill) {
            CreateBillPdfJob::dispatch($bill)->onQueue('createBillPdf');
        }
    }
}
