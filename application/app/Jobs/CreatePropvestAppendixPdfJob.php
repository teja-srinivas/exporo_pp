<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bill;
use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Services\PdfGenerator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\Filesystem;

class CreatePropvestAppendixPdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Bill */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
//        $pdf = $pdf->render($url->signedRoute('bills.pdf', [$this->bill]));

        //$this->store($filesystem->cloud(), $pdf);

        $document = new Document(['name' => 'testname2', 'description' => 'testdic2']);
        $document->user()->associate($this->user);
        $document->filename = 'bla2'; //Storage::disk(Document::DISK)->put(Document::DIRECTORY, $request->file('file'));
        $document->saveOrFail();
    }

//    private function store(Filesystem $disk, string $result): void
//    {
//        $fileSuffix = env('FILESYSTEM_CLOUD') === 'local' ? '.pdf' : '';
//        $disk->put("statements/{$this->bill->getKey()}{$fileSuffix}", $result, 'private');
//    }
}
