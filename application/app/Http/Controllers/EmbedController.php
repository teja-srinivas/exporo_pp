<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Embed;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class EmbedController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $this->authorize(Embed::class);

        $links = Link::query()
            ->whereIn('title', array_keys(Embed::$linkTitles))
            ->get()
            ->map(static function (Link $link) {
                return [
                    'title' => $link->title,
                    'url' => $link->userInstance->toHtml(),
                    'type' => Embed::$linkTitles[$link->title],
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
        $getFunded = false;

        $data = $this->validate($request, [
            'height' => ['required', 'in:530'],
            'width' => ['required', 'in:770,345'],
            'link' => ['required','url'],
            'type' => ['nullable','in:equity,finance'],
        ]);

        if (isset($data['type'])) {
            switch ($data['type']) {
                case "equity":
                    $type = "Exporo Financing";

                    break;
                case "finance":
                    $type = "Exporo Bestand";

                    break;
                default:
                    $type = null;
            }
        }

        $count = Project::query()
            ->where('funding_target', '>', 0)
            ->where(static function (Builder $query) {
                $query->where('status', Project::STATUS_IN_FUNDING);
                $query->orWhere('status', Project::STATUS_COMING_SOON);
            })->count();

        if ($count === 0) {
            $getFunded = true;
        }

        $query = Project::query()
            ->where('funding_target', '>', 0)
            ->where(static function (Builder $query) use ($getFunded) {
                $query->where('status', Project::STATUS_IN_FUNDING);
                $query->orWhere('status', Project::STATUS_COMING_SOON);

                if (!$getFunded) {
                    return;
                }

                $query->orWhere('status', Project::STATUS_FUNDED);
            });

        if (isset($type)) {
            $query->where('type', $type);
        }

        $query->whereNotNull('type');
        $query->whereNotNull('image');

        if ($getFunded) {
            $query->take(5);
        }

        $projects = $query->get()->map(static function (Project $project) {
            $investmentSum = $project->investments()
                ->whereNull('cancelled_at')
                ->sum('amount');

            switch ($project->type) {
                case "Exporo Financing":
                    $type = "equity";

                    break;
                case "Exporo Bestand":
                    $type = "finance";

                    break;
                default:
                    $type = null;
            }

            return [
                'name' => $project->description,
                'location' => $project->location,
                'coupon_rate' => $project->coupon_rate,
                'interest_rate' => $project->interest_rate,
                'intermediator' => $project->intermediator,
                'image' => $project->imageUrl(),
                'type' => $type,
                'status' => $project->status,
                'rating' => $project->rating,
                'funding_target' => $project->funding_target,
                'funding_current_sum_invested' => min($investmentSum, $project->funding_target),
                'placeholders' => Embed::$placeholders[$type],
            ];
        })->all();

        return view('affiliate.embeds.show', [
            'projects' => $projects,
            'data' => $data,
        ]);
    }
}
