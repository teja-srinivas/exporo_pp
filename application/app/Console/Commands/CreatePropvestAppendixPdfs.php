<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CreatePropvestAppendixPdfJob;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;

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

        $this->line("Creating PDFs for Propvest for " . $users->count() . " Users");

        foreach ($users as $user) {
            CreatePropvestAppendixPdfJob::dispatch($user)->onQueue('default');
        }
    }
}
