<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Commission as Model;

class Commission extends JsonResource
{
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
            'net' => (float)$model->net,
            'gross' => (float)$model->gross,
            'onHold' => $model->on_hold,
            'note' => [
                'private' => (string)$model->note_private,
                'public' => (string)$model->note_public,
            ],
            'reviewed' => $model->reviewed_at !== null /*$this->when($this->reviewed_at, [
                'date' => $this->reviewed_at,
                'user' => $this->when($this->reviewed_by, function () {
                    return User::make($this->reviewed_by);
                }),
            ], null)*/,
            'rejected' => $model->rejected_at !== null /* $this->when($this->rejected_at, [
                'date' => $this->rejected_at,
                'user' => $this->when($this->rejected_by, function () {
                    return User::make($this->rejected_by);
                }),
            ], null)*/,
            'user' => User::make($model->user),
            'model' => Investment::make($model->model),
        ];
    }
}
