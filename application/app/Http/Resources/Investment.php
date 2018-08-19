<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Investment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getRouteKey(),
            'investor' => Investor::make($this->investor),
            'project' => [
                'name' => $this->project->name,
                'schema' => $this->project->schema->formula,
            ],
        ];
    }
}
