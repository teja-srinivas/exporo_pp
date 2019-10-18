<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use App\Services\PdfGenerator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\Filesystem;

class CreateBillPdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        $pdf = $pdf->render($url->signedRoute('bills.pdf', [$this->bill]));

        $this->store($filesystem->cloud(), $pdf);

        $this->bill->pdf_created_at = now();
        $this->bill->save();
    }

    private function store(Filesystem $disk, string $result): void
    {
        $disk->put("statements/{$this->bill->getKey()}", $result, 'private');
    }
}
