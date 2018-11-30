<?php

namespace App\Listeners;

use App\Events\SchemaUpdated;
use App\Models\Commission;
use App\Models\Investment;
use App\Models\Project;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvalidateInvestentCommissionsOnSchemaChanges
{
    /**
     * Handle the event.
     *
     * @param  SchemaUpdated  $event
     * @return void
     */
    public function handle(SchemaUpdated $event)
    {
        Commission::query()
            ->where('model_type', Investment::MORPH_NAME)
            ->whereIn('model_id', Investment::query()
                ->join('projects', 'projects.id', 'investments.project_id')
                ->join('schemas', 'schemas.id', 'projects.schema_id')
                ->where('schemas.id', $event->schema->id)
                ->select('investments.id')
            )
            ->isOpen()
            ->delete();
    }
}
