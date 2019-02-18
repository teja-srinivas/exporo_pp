<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class CreateCompanyBucket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:companybucket {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a bucket for specified Company';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companyId = Company::query()->whereKey($this->argument('company'))->value('id');
        $s3 = Storage::disk('s3');

        $s3->makeDirectory($companyId . '/agbs');
        $s3->makeDirectory($companyId . '/banners');
        $s3->makeDirectory($companyId . '/preview');
        $s3->makeDirectory($companyId . '/statements');
    }
}
