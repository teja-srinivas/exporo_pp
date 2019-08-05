<?php

namespace App\Http\ViewComposers;

use App\Models\Agb;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class RegisterComposer
{
    /**
     * @var Collection
     */
    private $agbs;

    public function __construct()
    {
        $this->agbs = collect(Agb::TYPES)->mapWithKeys(function (string $type) {
            return [$type => route('agbs.latest', $type)];
        });
    }

    public function compose(View $view)
    {
        $view->with('agbs', $this->agbs);
    }
}
