<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Project;
use Illuminate\View\View;
use App\Builders\Builder;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;

class EmbedController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $links = Link::query()
            ->get()
            ->map(static function (Link $link) {
                return [
                    'title' => $link->title,
                    'url' => $link->userInstance->toHtml(),
                ];
            });

        return view('affiliate.embeds.index', [
            'links' => $links,
        ]);
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function show(Request $request)
    {
        $data = $this->validate($request, [
            'height' => ['required', 'in:530'],
            'width' => ['required', 'in:770,345'],
            'link' => ['required','url'],
            'type' => ['nullable','in:stock,finance'],
        ]);

        $query = Project::query()
            ->where('funding_target', '>', 0)
            ->where(function (Builder $query) {
                $query->where('status', Project::STATUS_IN_FUNDING);
                $query->orWhere('status', Project::STATUS_COMING_SOON);
            });

        if (isset($data['type'])) {
            $query->where('type', $data['type']);
        }

        $projects = $query->get()->map(static function (Project $project) {
            $investmentSum = $project->investments()
                ->whereNull('cancelled_at')
                ->sum('amount');

            return [
                'name' => $project->description,
                'location' => $project->location,
                'coupon_rate' => $project->coupon_rate,
                'interest_rate' => $project->interest_rate,
                'intermediator' => $project->intermediator,
                'image' => $project->imageUrl(),
                'type' => $project->type,
                'status' => $project->status,
                'funding_target' => min($investmentSum, $project->funding_target),
                'funding_current_sum_invested' => $investmentSum,
            ];
        })->all();

        return view('affiliate.embeds.show', [
            'projects' => $projects,
            'data' => $data,
        ]);
    }
}
