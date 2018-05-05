<?php

namespace Tests\Browser;

use App\User;
use App\UserDetails;
use DummyDataSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RegisterPage;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Checks if users are able to register on the site.
     *
     * @return void
     * @throws \Throwable
     */
    public function testUserRegistration()
    {
        $this->seed();

        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->make();
            $details = factory(UserDetails::class)->make();

            $browser->visit(new RegisterPage())
                    ->registerWith($user, $details)
                    ->press(strtoupper(__('Register')))
                    ->assertAuthenticated();
        });
    }
}
