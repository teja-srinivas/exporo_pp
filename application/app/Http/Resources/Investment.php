<?php

declare(strict_types=1);

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
            'interestRate' => number_format($investment->interest_rate, 2, ',', '').'%',
            'isFirst' => $investment->is_first_investment,
            'createdAt' => $investment->created_at->format('d.m.Y'),
            'project' => [
                'name' => $investment->project->description,
                'schema' => $investment->project->schema->formula,
                'margin' => $investment->project->marginPercentage(),
                'interestRate' => number_format($investment->project->interest_rate, 2, ',', '').'%',
                'runtimeFactor' => $investment->project->runtimeFactor(),
                'links' => [
                    'self' => route('projects.show', $investment->project),
                ],
            ],
        ];
    }
}
