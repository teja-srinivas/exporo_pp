<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserDetailsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_updates_the_display_name()
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
    public function it_formats_IBAN_strings()
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
}
