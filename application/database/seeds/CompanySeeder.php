<?php

declare(strict_types=1);

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Creates the main company model.
     *
     * @return Company
     */
    public function run(): Company
    {
        return factory(Company::class)->create([
            'name' => 'Exporo AG',
            'street' => 'Am Sandtorkai',
            'street_no' => '70',
            'postal_code' => '20457',
            'city' => 'Hamburg',
            'phone_number' => '040 210917300',
            'fax_number' => null,
            'email' => 'abrechnung@exporo.com',
            'website' => 'www.exporo.de',
        ]);
    }
}
