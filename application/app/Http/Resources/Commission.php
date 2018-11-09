<?php

namespace App\Http\Resources;

use App\Models\Commission as Model;
use Illuminate\Http\Resources\Json\JsonResource;

class Commission extends JsonResource
{
    private const RESOURCES = [
        \App\Models\Investment::MORPH_NAME => Investment::class,
        \App\Models\Investor::MORPH_NAME => Investor::class,
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
            'id' => $this->id,
            'key' => $this->getRouteKey(),
            'type' => $model->model_type,
            'net' => $model->net,
            'gross' => $model->gross,
            'vatIncluded' => $model->user->details->vat_included,
            'bonus' => $model->bonus,
            'onHold' => $model->on_hold,
            'note' => [
                'private' => $model->note_private,
                'public' => $model->note_public,
            ],
            'reviewed' => $model->reviewed_at !== null,
            'rejected' => $model->rejected_at !== null,
            'user' => User::make($model->user),
            'childUser' => $this->when($model->child_user_id > 0, function () use ($model) {
                return User::make($model->childUser);
            }),
            'model' => (self::RESOURCES[$model->model_type])::make($model->model),
        ];
    }
}
