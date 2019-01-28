<?php

namespace App\Listeners;

use App\Events\ProjectUpdated;
use App\Models\Commission;
use App\Models\Investment;
use Illuminate\Database\Query\Builder;

class InvalidateInvestmentCommissionsOnProjectChanges
{
    /**
     * Handle the event.
     *
     * @param  ProjectUpdated  $event
     * @return void
     */
    public function handle(ProjectUpdated $event)
    {
        Commission::query()
            ->join('investments', 'investments.id', 'commissions.model_id')
            ->where('model_type', Investment::MORPH_NAME)
            ->where('investments.project_id', $event->project->getKey())
            ->isRecalculatable()
            ->delete();
    }
}
