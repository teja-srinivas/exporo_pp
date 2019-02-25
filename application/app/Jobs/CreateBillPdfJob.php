<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Services\PdfGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Routing\UrlGenerator;

class CreateBillPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Bill */
    protected $bill;


    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * @param UrlGenerator $url
     * @param PdfGenerator $pdf
     * @param FilesystemManager $filesystem
     * @return void
     * @throws \DocRaptor\ApiException
     */
    public function handle(UrlGenerator $url, PdfGenerator $pdf, FilesystemManager $filesystem)
    {
        $pdf = $pdf->render($url->signedRoute('bills.pdf', $this->bill));

        $this->store($filesystem->cloud(), $pdf);

        $this->bill->pdf_created = true;
        $this->bill->save();
    }

    private function store(Filesystem $disk, string $result): void
    {
        $disk->put("statements/{$this->bill->getKey()}", $result, 'private');
    }
}
