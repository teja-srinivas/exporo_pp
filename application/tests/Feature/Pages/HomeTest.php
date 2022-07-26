<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class HomeTest extends TestCase
{
    use WithoutMiddleware;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_shows_latest_bills()
    {
        $bill = $this->createBill(['released_at' => '2018-11-01']);
        $billWithoutPdf = $this->createBill(['released_at' => '2018-12-01', 'pdf_created_at' => null]);

        $this->be($this->user);

        $response = $this->get(route('accounting'));

        $response->assertOk();

        $response->assertSee($bill->getDisplayName());
        $response->assertDontSee($billWithoutPdf->getDisplayName());
    }

    protected function createBill(array $attributes = []): Bill
    {
        /** @var Bill $bill */
        $bill = factory(Bill::class)->state('released')->create($attributes + [
            'user_id' => $this->user->getKey(),
        ]);

        $commission = $bill->commissions()->forceCreate([
            'user_id' => $this->user->getKey(),
            'net' => 10,
            'gross' => 10,
        ]);

        $bill->commissions()->save($commission);

        return $bill;
    }
}
