<?php

namespace App\Http\ViewComposers;

use App\Agb;
use Illuminate\View\View;

class RegisterComposer
{
    /**
     * @var Agb
     */
    private $agb;


    public function __construct()
    {
        $this->agb = Agb::current();
    }

    public function compose(View $view)
    {
        $view->with('agb', $this->agb ?: new Agb());
    }
}
