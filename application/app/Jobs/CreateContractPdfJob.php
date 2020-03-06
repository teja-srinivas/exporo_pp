<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use App\Services\PdfGenerator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\Filesystem;

class CreateContractPdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Contract */
    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
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
        //$pdf = $pdf->render($url->signedRoute('contract-pdf.show', [$this->contract]));

        //$this->store($filesystem->cloud(), $pdf);

        $this->contract->pdf_generated_at = now();
        $this->contract->save();
    }

    private function store(Filesystem $disk, string $result): void
    {
        $disk->put($this->contract->getFilePath(), $result, 'private');
    }
}
