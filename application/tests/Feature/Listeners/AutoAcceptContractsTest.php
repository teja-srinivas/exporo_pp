<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Tests\Traits\CreatesUsers;
use App\Models\ProductContract;
use App\Models\PartnerContract;

class AutoAcceptContractsTest extends TestCase
{
    use CreatesUsers;

    /** @test */
    public function it_auto_accepts_contracts(): void
    {
        Carbon::setTestNow('1.2.2019 12:45:56');

        /** @var ProductContract $contract */
        $contract = factory(ProductContract::class)->create([
            'user_id' => $this->createActiveUser()->getKey(),
        ]);

        $contract->released_at = now();
        $contract->save();

        $this->assertEquals($contract->released_at, $contract->accepted_at);
    }

    /** @test */
    public function it_does_not_run_for_partner_contracts_when_user_has_accept_permission(): void
    {
        /** @var PartnerContract $contract */
        $contract = factory(PartnerContract::class)->create([
            'user_id' => $this->createActiveUserWithPermission(
                'features.contracts.accept'
            )->getKey(),
        ]);

        $contract->released_at = now();
        $contract->save();

        $this->assertNull($contract->accepted_at);
    }

    /** @test */
    public function it_runs_for_product_contracts_even_if_user_has_accept_permission(): void
    {
        /** @var ProductContract $contract */
        $contract = factory(ProductContract::class)->create([
            'user_id' => $this->createActiveUserWithPermission(
                'features.contracts.accept'
            )->getKey(),
        ]);

        $contract->released_at = now();
        $contract->save();

        $this->assertNotNull($contract->accepted_at);
    }
}
