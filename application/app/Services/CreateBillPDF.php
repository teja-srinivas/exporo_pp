<?php

namespace App\Services;

use GuzzleHttp\Client;

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
            'delay' => 6500,
            'transparent' => 'true',
            'pdf_page_size' => 'A4',
            'pdf_background' => 'true',
            'pdf_margin' => 'minimum',
            'retina' => true
        ];
    }

    public function getPDF(string $url, $type = 'pdf')
    {
        $url = env('URLBOX_BASE_URL') . $type;
        $this->globalsParams['url'] = $url;

        return $this->getRequest($url, $this->globalsParams);
    }

    protected function getRequest(string $url, array $params)
    {
        $url = env('URLBOX_BASE_URL') . '?' . http_build_query($params);
        $res = $this->client->request('GET', $url);
        return $res->getBody();
    }
}
