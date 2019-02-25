<?php

namespace Tests\Unit\Models;

use App\Models\User;
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
}
