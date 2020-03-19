<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Embed;
use App\Models\Project;

trait ProjectTrait
{
    /**
     * Check if project can be displayed in iframe.
     *
     * @param  Project  $project
     * @return string|null
     */
    public function checkForError(Project $project): ?string
    {
        $errors = [];

        if ($project->funding_target === 0) {
            $errors[] = "Finanzierungsziel ist 0.";
        }

        if ($project->type === null) {
            $errors[] = "Es ist kein Projekttyp angegeben.";
        }

        if ($project->image === null) {
            $errors[] = "Es ist kein Bild vorhanden.";
        }

        if (!in_array($project->status, Project::IFRAME_STATUSES)) {
            $string = implode(", ", Project::IFRAME_STATUSES);
            $errors[] = "Status erforderlich: {$string}.";
        }

        if (!in_array($project->legal_setup, Embed::$legalSetup)) {
            $string = implode(", ", Embed::$legalSetup);
            $errors[] = "Rechtliche Rahmenbedingungen erforderlich: {$string}.";
        }

        if (count($errors) > 0) {
            return implode(" ", $errors);
        }

        return null;
    }
}
