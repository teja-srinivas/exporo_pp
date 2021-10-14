<?php

declare(strict_types=1);

namespace App\Jobs;
/*
 * Preview URL: http://localhost/propvest/preview/136930
 */
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
use Illuminate\Support\Str;

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
        $pdf = $pdf->render($url->signedRoute('propvest.pdf', [$this->user]));

        $filename = $this->store($filesystem->cloud(), $pdf);

        $document = new Document(['name' => 'testname3', 'description' => 'testdic2']);
        $document->user()->associate($this->user);
        $document->filename = $filename; //Storage::disk(Document::DISK)->put(Document::DIRECTORY, $request->file('file'));
        $document->saveOrFail();
    }

    private function store(Filesystem $disk, string $result): string
    {
        $randomUuid = Str::uuid();
        $fileSuffix = env('FILESYSTEM_CLOUD') === 'local' ? '.pdf' : '';
        $path = "documents/propvest_{$randomUuid}{$fileSuffix}";
        $disk->put($path, $result, 'private');
        return $path;
    }
}
