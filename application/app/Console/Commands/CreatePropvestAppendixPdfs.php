<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CreateBillPdfJob;
use App\Jobs\CreatePropvestAppendixPdfJob;
use App\Models\Document;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreatePropvestAppendixPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'propvest:pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the appendix Propvest PDF files';

    public function handle(UserRepository $repository)
    {
        $users = $repository->withoutPropvestPdf();

        $this->line("Creating PDFs for Propvest");

        foreach ($users as $user) {
            $this->line("User", $user->email);
            CreatePropvestAppendixPdfJob::dispatch($user)->onQueue('createPropvestAppendixPdf');

//            $document = new Document(['name' => 'testname', 'description' => 'testdic']);
//            $document->user()->associate($user);
//            $document->filename = 'bla'; //Storage::disk(Document::DISK)->put(Document::DIRECTORY, $request->file('file'));
//            $document->saveOrFail();
        }
    }
}
