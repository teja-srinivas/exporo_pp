<?php

namespace App\Http\Helper\Request;

class Field
{
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';

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
