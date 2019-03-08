<?php

namespace App\Console\Commands;

use App\Models\UserDetails;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CleanEmptyFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:clean-empty-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replaces all seemingly empty fields with a proper NULL value';

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
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->clean(UserDetails::class, 'address_city', '');
            $this->clean(UserDetails::class, 'address_number');
            $this->clean(UserDetails::class, 'address_street', '');
            $this->clean(UserDetails::class, 'address_zipcode', '');
            $this->clean(UserDetails::class, 'bic');
            $this->clean(UserDetails::class, 'company', '');
            $this->clean(UserDetails::class, 'iban');
            $this->clean(UserDetails::class, 'phone');
            $this->clean(UserDetails::class, 'tax_office', '');
            $this->clean(UserDetails::class, 'vat_id');
            $this->clean(UserDetails::class, 'website', '');
        });
    }

    protected function clean(string $class, string $field, string $value = '0')
    {
        /** @var Model $model */
        $model = new $class;

        $this->line("Cleaning '$field' in '{$model->getTable()}' table where value is '$value'");

        $model->newQuery()->where($field, $value)->update([$field => null]);
    }
}
