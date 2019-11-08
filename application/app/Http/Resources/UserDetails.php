<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\UserDetails $model */
        $model = $this->resource;

        return [
            'id' => $model->getKey(),
            'displayName' => trim($model->display_name),
            'links' => [
                'self' => route('users.show', $model),
            ],
        ];
    }
}
