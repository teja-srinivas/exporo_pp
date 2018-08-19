<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Commission as Model;

class Commission extends JsonResource
{
    private const RESOURCES = [
        \App\Investment::MORPH_NAME => Investment::class,
        \App\Investor::MORPH_NAME => Investor::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Model $model */
        $model = $this->resource;

        return [
            'id' => $this->getRouteKey(),
            'type' => $model->model_type,
            'net' => (float)$model->net,
            'gross' => (float)$model->gross,
            'onHold' => $model->on_hold,
            'note' => [
                'private' => (string)$model->note_private,
                'public' => (string)$model->note_public,
            ],
            'reviewed' => $model->reviewed_at !== null,
            'rejected' => $model->rejected_at !== null,
            'user' => User::make($model->user),
            'model' => (self::RESOURCES[$model->model_type])::make($model->model),
        ];
    }
}
