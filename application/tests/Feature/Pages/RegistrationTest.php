<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use Tests\TestCase;
use App\Models\User;
use App\Models\Contract;
use App\Models\CommissionBonus;
use App\Models\ContractTemplate;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductContractTemplate;
use Illuminate\Foundation\Testing\WithFaker;

class RegistrationTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_allows_user_registration()
    {
        /** @var ContractTemplate $template */
        $template = factory(ProductContractTemplate::class)->create();
        $template->bonuses()->save(factory(CommissionBonus::class)->make());

        $template->company->contractTemplate()->associate($template);
        $template->company->save();

        $response = $this->post(route('register'), [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,

            'salutation' => 'male',
            'birth_year' => 1950,
            'birth_month' => 3,
            'birth_day' => 12,
            'address_street' => $this->faker->streetAddress,
            'address_number' => (string) $this->faker->numberBetween(1, 10),
            'address_zipcode' => (string) 20123,
            'address_city' => 'Hamburg',
            'phone' => '+49 40 12 34 56 7',

            'legal_exporo_ag' => true,
            'legal_exporo_gmbh' => true,
            'legal_transfer' => true,
            'cookie_advertisement' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home'));

        $this->assertAuthenticated();

        /** @var User $user */
        $user = Auth::user();

        // See if a contract got created based on the current template
        // Get the contract by hand as the normal 'contract' only returns accepted ones
        /** @var Contract $contract */
        $contract = $user->contracts()->first();

        $this->assertNotNull($contract);
        $this->assertTrue($contract->template->is($template));

        $this->assertNotEmpty($contract->bonuses);
        $this->assertNotEquals($contract->bonuses->first()->getKey(), $template->bonuses->first()->getKey());
    }
}
