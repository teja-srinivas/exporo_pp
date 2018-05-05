<?php

namespace Tests\Browser\Pages;

use App\User;
use App\UserDetails;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class RegisterPage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('register');
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertUrlIs($this->url());
        $browser->assertSee(__('Register'));
    }

    /**
     * Fills the registration with the provided data.
     *
     * @param Browser $browser
     * @param User $user
     * @param UserDetails $details
     */
    public function registerWith(Browser $browser, User $user, UserDetails $details)
    {
        $salutationId = $details->salutation === 'female' ? 1 : 2;

        $browser->type('first_name', $user->first_name);
        $browser->type('last_name', $user->last_name);
        $browser->click("label[for=salutation{$salutationId}]");
        $browser->select('birth_day', $details->birth_date->day);
        $browser->select('birth_month', $details->birth_date->month);
        $browser->select('birth_year', $details->birth_date->year);
        $browser->type('birth_place', $details->birth_place);
        $browser->type('address_zipcode', $details->address_zipcode);
        $browser->type('address_city', $details->address_city);
        $browser->type('email', $user->email);
        $browser->type('phone', $details->phone);
        $browser->type('password', 'secret');
        $browser->type('password_confirmation', 'secret');
        $browser->check('label[for=legal_exporo_ag]');
        $browser->check('label[for=legal_exporo_gmbh]');
        $browser->check('label[for=legal_transfer]');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@submit' => strtoupper(__('Register')),
        ];
    }
}
