<?php

namespace App\Listeners;

use App\Events\ProjectUpdated;
use App\Models\Commission;
use App\Models\Investment;

class InvalidateProjectCommissions
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
            ->where('model_type', Investment::MORPH_NAME)
            ->whereIn('model_id', $event->project->investments()->pluck('id'))
            ->isOpen()
            ->delete();
    }
}
