<?php

namespace App\Console\Commands;

use App\Models\UserDetails;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CleanEmptyFields extends Command
{
    protected $signature = 'clean:empty-fields';
    protected $description = 'Replaces all seemingly empty fields with a proper NULL value';

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
