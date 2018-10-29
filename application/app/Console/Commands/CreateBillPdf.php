<?php

namespace App\Console\Commands;

use App\Jobs\createBillPdfJob;
use App\Models\Bill;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
class CreateBillPdf extends Command
{
    protected $globalParams;
    protected  $url;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:billpdf';

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
        $this->url = url()->current() . '/bills/pdf/';
        $this->globalsParams = [
            'force' => 'true',
            'full_page' => true,
            'delay' => 0,
            'transparent' => 'true',
            'pdf_page_size' => 'A4',
            'pdf_background' => 'true',
            'pdf_margin' => 'minimum',
            'auth' => config('urlbox.auth_key'),
            'retina' => false,
            'ttl' => 0,
        ];
        parent::__construct();
    }

    public function handle()
    {
        $bills = $this->getReleasedBills();
        foreach ($bills as $bill) {
           createBillPdfJob::dispatch($bill)->onQueue('createBillPdf');
        }
    }

    private function getReleasedBills()
    {
        return (Bill::where('released_at', now()->format('Y-m-d'))->get());
    }
}
