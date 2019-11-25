<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommissionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CommissionTypeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CommissionType::class, 'type');
    }

    public function index(): Response
    {
        return response()->view('commissions.types.index', [
            'types' => CommissionType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('commissions.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $data['is_project_type'] = $request->has('is_project_type');
        $data['is_public'] = $request->has('is_public');

        CommissionType::query()->create($data);

        flash_success('Provisionstyp wurde angelegt');

        return redirect()->route('commissions.types.index');
    }

    /**
     * @param CommissionType $type
     * @return Response
     */
    public function show(CommissionType $type): Response
    {
        $projects = $type->is_project_type
            ? $type->projects()->get()->map(static function (Project $project) {
                return [
                    'id' => $project->id,
                    'project' => $project->description,
                    'createdAt' => optional($project->created_at)->format('Y-m-d'),
                    'links' => [
                        'self' => route('projects.show', $project),
                    ],
                ];
            })->values()
            : [];

        return response()->view('commissions.types.show', compact('type', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CommissionType $type
     * @return Response
     */
    public function edit(CommissionType $type): Response
    {
        return response()->view('commissions.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  CommissionType $type
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, CommissionType $type): RedirectResponse
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $data['is_project_type'] = $request->has('is_project_type');
        $data['is_public'] = $request->has('is_public');

        $type->fill($data)->save();

        flash_success('Provisionstyp wurde aktualisiert');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CommissionType  $type
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(CommissionType $type): RedirectResponse
    {
        $type->delete();

        flash_success('Provisionstyp wurde gelöscht');

        return response()->redirectToRoute('commissions.types.index');
    }
}
