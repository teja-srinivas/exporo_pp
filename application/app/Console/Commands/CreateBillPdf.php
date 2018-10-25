<?php

namespace App\Console\Commands;

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
        $this->url = 'e248b0a7.eu.ngrok.io/bills/pdf/';
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

    public function handle($type = 'pdf')
    {
        $bills = $this->getReleasedBills();
        foreach ($bills as $bill) {
            $urlBoxUrl = config('services.urlbox.url') . $type;
            $this->globalsParams['url'] = $this->url . $bill['id'];
            $this->storeInS3($this->getRequest($urlBoxUrl, $bill));
        }
    }

    private function getReleasedBills()
    {
        return (Bill::where('released_at', now()->format('Y-m-d'))->get());
    }

    private function getRequest(string $url)
    {
        $client = new Client();
        $url = $url . '?' . http_build_query($this->globalsParams) . '&authorization=Basic%20YS52ZXJ0Z2V3YWxsQGV4cG9yby5jb206MTIzNDU2';
        $res = $client->request('GET', $url);
        return $res->getBody()->getContents();
    }

    private function storeInS3($result, $bill)
    {
        Storage::disk('s3')->put('statements/' . $bill['id'] . '.pdf'
            , $result, 'private');
    }

}
