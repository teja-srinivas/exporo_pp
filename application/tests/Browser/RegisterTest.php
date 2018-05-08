<?php

namespace Tests\Browser;

use App\User;
use App\UserDetails;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RegisterPage;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as runMigrations;
    }

    /**
     * Checks if users are able to register on the site.
     *
     * @return void
     * @throws \Throwable
     */
    public function testUserRegistration()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->make();
            $details = factory(UserDetails::class)->make();

            $browser->visit(new RegisterPage())
                    ->registerWith($user, $details)
                    ->press(strtoupper(__('Register')))
                    ->assertAuthenticated()
                    ->logout();
        });
    }

    /**
     * Checks if we're showing an error if values are missing.
     *
     * @throws \Throwable
     */
    public function testMissingDetails()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->make();

            $browser->visit(new RegisterPage())
                ->registerWith($user, new UserDetails)
                ->press(strtoupper(__('Register')))
                ->assertUrlIs(route('register'));
        });
    }
}
