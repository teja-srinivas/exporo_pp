<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Arr;
use App\Models\CommissionBonus;
use App\Models\ProductContract;
use App\Models\PartnerContract;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductContractTemplate;
use App\Models\PartnerContractTemplate;
use Illuminate\Foundation\Testing\WithFaker;

class RegistrationTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_allows_user_registration()
    {
        // Create contracts
        /** @var ProductContractTemplate $productTemplate */
        $productTemplate = factory(ProductContractTemplate::class)->create([
            'is_default' => true,
        ]);

        $productTemplate->bonuses()->save(factory(CommissionBonus::class)->make());

        /** @var PartnerContractTemplate $partnerTemplate */
        $partnerTemplate = factory(PartnerContractTemplate::class)->create([
            'is_default' => true,
            'company_id' => $productTemplate->company_id,
        ]);

        // Call route
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
        $contracts = $user->contracts;
        $this->assertCount(2, $contracts);

        /** @var ProductContract $contract */
        $contract = $contracts->shift();
        $this->assertTrue($contract->template->is($productTemplate));
        $this->assertEquals([
            'vat_amount' => $productTemplate->vat_amount,
            'vat_included' => $productTemplate->vat_included,
        ], Arr::only($contract->getAttributes(), ['vat_amount', 'vat_included']));
        $this->assertNotEmpty($contract->bonuses);
        $this->assertNotEquals($contract->bonuses->first()->getKey(), $productTemplate->bonuses->first()->getKey());

        /** @var PartnerContract $contract */
        $contract = $contracts->shift();
        $this->assertTrue($contract->template->is($partnerTemplate));
        $this->assertEquals([
            'cancellation_days' => $partnerTemplate->cancellation_days,
            'claim_years' => $partnerTemplate->claim_years,
        ], Arr::only($contract->getAttributes(), ['cancellation_days', 'claim_years']));
    }
}
