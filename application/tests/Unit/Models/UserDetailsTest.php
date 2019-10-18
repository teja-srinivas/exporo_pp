<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserDetails;

class UserDetailsTest extends TestCase
{
    /** @test */
    public function it_updates_the_display_name(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'first_name' => 'Peter',
            'last_name' => 'Pan',
        ]);

        $this->assertEquals('P. Pan', $user->details->display_name);

        $user->fill(['last_name' => 'Lustig'])->save();

        $this->assertEquals('P. Lustig', $user->details->display_name);

        $user->details->fill(['company' => 'Exporo'])->save();

        $this->assertEquals('Exporo', $user->details->display_name);
    }

    /** @test */
    public function it_formats_IBAN_strings(): void
    {
        $details = new UserDetails();

        $details->iban = 'NL53ABNA2560035731';
        $this->assertSame('NL53 ABNA 2560 0357 31', $details->getFormattedIban());

        $details->iban = '  DE7 85 00105173 532649 64 8';
        $this->assertSame('DE78 5001 0517 3532 6496 48', $details->getFormattedIban());

        $details->iban = '';
        $this->assertSame('', $details->getFormattedIban());

        $details->iban = null;
        $this->assertSame(null, $details->getFormattedIban());
    }

    /**
     * @param  string  $country
     * @param  string|null  $id
     * @param  bool  $result
     * @dataProvider vatIds
     * @test
     */
    public function it_determines_the_country_based_on_vat_ids(string $country, ?string $id, bool $result): void
    {
        $details = new UserDetails(['vat_id' => $id]);
        $this->assertSame($result, $details->isFromCountry($country));
    }

    public function vatIds()
    {
        return [
            ['DE', 'DE128575725', true],
            ['DE', 'BE128575725', false],
            ['DE', null, false],
            ['DE', 'D', false],
            ['DE', 'D1E', false],
        ];
    }
}
