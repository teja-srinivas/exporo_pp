<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\PartnerContract;
use App\Models\ProductContract;
use Illuminate\Console\Command;
use App\Jobs\CreateContractPdfJob;

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
        //product contracts
        $productContracts = Contract::query()
            ->where('type', ProductContract::STI_TYPE)
            ->onlyActive()
            ->withoutPdf()
            ->get();

        $this->line("Creating PDFs for {$productContracts->count()} contract(s)");

        foreach ($productContracts as $productContract) {
            CreateContractPdfJob::dispatch($productContract)->onQueue('default');
        }

        //partner contracts
        $partnerContracts = Contract::query()
            ->where('type', PartnerContract::STI_TYPE)
            ->onlyActive()
            ->whereSigned()
            ->withoutPdf()
            ->get();

        $this->line("Creating PDFs for {$partnerContracts->count()} contract(s)");

        foreach ($partnerContracts as $partnerContract) {
            CreateContractPdfJob::dispatch($partnerContract)->onQueue('default');
        }
    }
}
