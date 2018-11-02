<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
class createBillPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bill;
    protected  $type;
    protected $globalsParams;
    protected $url;

    public function __construct($bill, $type = 'pdf')
    {
        $this->bill = $bill;
        $this->type = $type;
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $urlBoxUrl = config('services.urlbox.url') . $this->type;
        $this->globalsParams['url'] = $this->url . $this->bill['id'];
        $this->storeInS3($this->getRequest($urlBoxUrl));
        $this->bill->pdf_created = true;
        $this->bill->save();
    }

    private function getRequest(string $url)
    {
        $client = new Client();
        $url = $url . '?' . http_build_query($this->globalsParams) . '&authorization=Basic%20YS52ZXJ0Z2V3YWxsQGV4cG9yby5jb206MTIzNDU2';
        $res = $client->request('GET', $url);
        return $res->getBody()->getContents();
    }

    private function storeInS3($result)
    {
        Storage::disk('s3')->put('statements/' . $this->bill['id'] . '.pdf'
            , $result, 'private');
    }
}
