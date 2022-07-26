<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Commission;
use App\Models\Investment;
use App\Events\ProjectUpdated;

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
