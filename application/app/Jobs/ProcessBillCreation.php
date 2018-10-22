<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ProcessBillCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $globalsParams;
    protected $url;
    protected $bill;

    public function __construct($url, $bill)
    {
        $this->url = $url;
        $this->bill = $bill;
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

    public function handle($type = 'pdf')
    {
        $urlBoxUrl = env('URLBOX_BASE_URL') . $type;
        $this->globalsParams['url'] = $this->url . $this->bill['id'];
        $this->storeInS3($this->getRequest($urlBoxUrl));
    }

    protected function getRequest(string $url)
    {
        $client = new Client();
        $url = $url . '?' . http_build_query($this->globalsParams) . '&authorization=Basic%20YS52ZXJ0Z2V3YWxsQGV4cG9yby5jb206MTIzNDU2';
        $res = $client->request('GET', $url);
        return $res->getBody()->getContents();
    }

    protected function storeInS3($result){
        Storage::disk('s3')->put('statements/' . $this->bill['id'] . '.pdf'
            , $result, 'private');
    }
}
