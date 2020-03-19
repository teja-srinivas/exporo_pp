<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Traits\ProjectTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    use ProjectTrait;

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

        $message = $this->checkForError($project);

        if ($message !== null) {
            $project->in_iframe = false;
            $project->save();
            abort(422, $message);
        }

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

        Project::find($request->projects)->each(static function (Project $project) {
            $message = (new self())->checkForError($project);

            if ($message === null) {
                return;
            }

            $project->in_iframe = false;
            $project->save();
            abort(422, "{$project->description}: {$message}");
        });

        Project::whereIn('id', $request->projects)
            ->update(['in_iframe' => true]);

        Project::whereNotIn('id', $request->projects)
            ->update(['in_iframe' => false]);
    }
}
