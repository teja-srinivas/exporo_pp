<?php

namespace App\Http\Helper\Request;

class Field
{
    /** @var string */
    public $filter;

    /** @var string */
    public $order;

    public function __construct(string $filter, string $order)
    {
        $this->filter = $filter;
        $this->order = $order;
    }
}
