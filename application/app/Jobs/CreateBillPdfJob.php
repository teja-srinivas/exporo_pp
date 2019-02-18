<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Services\ApiTokenService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class CreateBillPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Bill */
    protected $bill;

    /** @var bool */
    protected $live;

    /** @var string */
    protected $type;

    /** @var array */
    protected $globalsParams;

    public function __construct(Bill $bill, bool $live = false, string $type = 'pdf')
    {
        $this->live = $live;
        $this->bill = $bill;
        $this->type = $type;
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
     * @param ApiTokenService $service
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(ApiTokenService $service)
    {
        $token = $service->forService('urlbox', $this->bill->user_id, $this->bill->id);
        $urlBoxUrl = config('services.urlbox.url') . $this->type;
        $this->globalsParams['url'] = route('pdf.creation', [$this->bill, $token]);

        $this->storeInS3($this->getRequest($urlBoxUrl));

        if ($this->live) {
            $this->bill->pdf_created = true;
            $this->bill->save();
        }
    }

    /**
     * @param string $url
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRequest(string $url): string
    {
        $client = new Client();
        $url = $url . '?' . http_build_query($this->globalsParams);
        $res = $client->request('GET', $url);
        return $res->getBody()->getContents();
    }

    private function storeInS3(string $result): void
    {
        $folder = $this->live ? 'statements' : 'preview';

        Storage::disk('s3')->put("{$folder}/{$this->bill->id}", $result, 'private');
    }
}
