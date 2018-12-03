<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectUpdated
{
    use SerializesModels;

    /**
     * @var Project
     */
    public $project;

    /**
     * Create a new event instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
