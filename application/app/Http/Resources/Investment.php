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
        /** @var \App\Models\Investment $investment */
        $investment = $this->resource;

        return [
            'id' => $this->getKey(),
            'investor' => Investor::make($investment->investor),
            'investment' => $investment->amount,
            'isFirst' => $investment->is_first_investment,
            'project' => [
                'name' => $investment->project->description,
                'schema' => $investment->project->schema->formula,
                'margin' => $investment->project->marginPercentage(),
                'runtimeFactor' => $investment->project->runtimeFactor(),
                'links' => [
                    'self' => route('projects.show', $investment->project),
                ],
            ],
        ];
    }
}
