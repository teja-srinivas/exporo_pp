<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
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
     * @return void
     */
    public function handle()
    {
        $id = $this->argument('company');

        if (Company::query()->whereKey($id)->doesntExist()) {
            $this->error('Company does not seem to exist');
            $this->setCode(1);

            return;
        }

        $s3 = Storage::disk('s3');
        $s3->makeDirectory("{$id}/agbs");
        $s3->makeDirectory("{$id}/banners");
        $s3->makeDirectory("{$id}/preview");
        $s3->makeDirectory("{$id}/statements");
    }
}
