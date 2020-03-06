<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CreateContractPdfJob;
use App\Models\Contract;
use App\Models\PartnerContract;
use Illuminate\Console\Command;

class CreateContractPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the cotnract PDF files';

    public function handle()
    {
        $contracts = Contract::query()
            ->where('type', PartnerContract::STI_TYPE)
            ->whereActive()
            ->whereSigned()
            ->withoutPdf()
            ->get();

        $this->line("Creating PDFs for {$contracts->count()} contract(s)");

        foreach ($contracts as $contract) {
            CreateContractPdfJob::dispatch($contract)->onQueue('createBillPdf');
        }
    }
}
