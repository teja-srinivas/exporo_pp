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
    protected $signature = 'create:companybucket {--company=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a bucket for specified Company';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $company = Company::find($this->option('company'));
       Storage::disk('s3')->makeDirectory($company['id'] . '/banners');
       Storage::disk('s3')->makeDirectory($company['id'] . '/agbs');
       Storage::disk('s3')->makeDirectory($company['id'] . '/preview');
       Storage::disk('s3')->makeDirectory($company['id'] . '/statements');
    }
}
