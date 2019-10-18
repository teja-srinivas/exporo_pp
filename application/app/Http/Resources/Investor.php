<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Investor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Investor $investor */
        $investor = $this->resource;

        return [
            'id' => $investor->id,
            'firstName' => trim($investor->first_name),
            'lastName' => trim($investor->last_name),
            'activatedAt' => optional($investor->activation_at)->format('d.m.Y'),
        ];
    }
}
