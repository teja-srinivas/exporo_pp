<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
final class CreateBillPDF
{
    protected $client;
    protected $globalsParams;

    public function __construct(Client $client)
    {
        $this->client = $client;

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

    public function getPDF(string $url, $bill, $type = 'pdf')
    {

        $urlBoxUrl = env('URLBOX_BASE_URL') . $type;
        $this->globalsParams['url'] = $url . $bill['id'];

        return $this->getRequest($urlBoxUrl, $this->globalsParams, $bill);
    }

    protected function getRequest(string $url, array $params, $bill)
    {

        $url = $url . '?' . http_build_query($params) . '&authorization=Basic%20YS52ZXJ0Z2V3YWxsQGV4cG9yby5jb206MTIzNDU2';
        $res = $this->client->request('GET', $url);

        Storage::disk('s3')->put('statements/'. $bill['id'] .'.pdf'
            , $res->getBody()->getContents(), 'private');
        return $res->getBody()->getContents();
    }
}
