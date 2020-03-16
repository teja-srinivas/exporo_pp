<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, Request $request)
    {
        $this->authorize('update', $project);

        $project->in_iframe = $request->in_iframe;
        $project->save();
    }

    /**
     * Update the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateMultiple(Request $request)
    {
        $this->authorize('update', Project::class);

        Project::whereIn('id', $request->projects)
            ->update(['in_iframe' => true]);

        Project::whereNotIn('id', $request->projects)
            ->update(['in_iframe' => false]);
    }
}
