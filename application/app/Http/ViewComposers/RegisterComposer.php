<?php

namespace App\Http\ViewComposers;

use App\Agb;
use Illuminate\View\View;

class RegisterComposer
{
    /**
     * @var Agb
     */
    private $agbs;


    public function __construct()
    {
        $this->agbs = collect(Agb::TYPES)->mapWithKeys(function (string $type) {
            return [$type => Agb::current($type) ?: new Agb()];
        });
    }

    public function compose(View $view)
    {
        $view->with('agbs', $this->agbs);
    }
}
