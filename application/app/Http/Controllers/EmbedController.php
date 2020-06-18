<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Embed;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
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
                $link->usage = 'embed';

                return [
                    'title' => $link->title,
                    'url' => $link->userInstance->toHtml('embed'),
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
        $type = null;

        $data = $this->validate($request, [
            'height' => ['required', 'in:530'],
            'width' => ['required', 'in:770,345'],
            'link' => ['required','url'],
            'type' => ['nullable','in:equity,finance'],
        ]);

        if (isset($data['type'])) {
            switch ($data['type']) {
                case "finance":
                    $type = "Exporo Financing";

                    break;
                case "equity":
                    $type = "Exporo Bestand";

                    break;
                default:
                    $type = null;
            }
        }

        $projects = $this->getProjects($type);

        if (count($projects) === 0) {
            $projects = $this->getProjects($type, true);
        }

        return view('affiliate.embeds.show', [
            'projects' => $projects,
            'data' => $data,
        ]);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function showJson(Request $request)
    {
        $type = null;

        $data = $this->validate($request, [
            'type' => ['nullable','in:equity,finance'],
        ]);

        if (isset($data['type'])) {
            switch ($data['type']) {
                case "finance":
                    $type = "Exporo Financing";

                    break;
                case "equity":
                    $type = "Exporo Bestand";

                    break;
                default:
                    $type = null;
            }
        }

        $projects = $this->getProjects($type);

        if (count($projects) === 0) {
            $projects = $this->getProjects($type, true);
        }

        return response()->json($projects);
    }

    /**
     * @param  String $type
     * @param bool $getFunded
     * @return Array
     */
    public function getProjects($type, $getFunded = false)
    {
        $query = Project::query()
            ->where('in_iframe', true)
            ->where('funding_target', '>', 0)
            ->where(static function (Builder $query) use ($getFunded) {
                $query->where('status', Project::STATUS_IN_FUNDING);
                $query->orWhere('status', Project::STATUS_COMING_SOON);

                if (!$getFunded) {
                    return;
                }

                $query->orWhere('status', Project::STATUS_FUNDED);
            });

        if ($type !== null) {
            $query->where('type', $type);
        }

        $query->whereNotNull('image');
        $query->whereNotNull('type');
        $query->whereIn('legal_setup', Embed::$legalSetup);

        if ($getFunded) {
            $query->inRandomOrder()->take(1);
        }

        return $query->get()->map(static function (Project $project) {
            $investmentSum = $project->investments()
                ->whereNull('cancelled_at')
                ->sum('amount');

            switch ($project->type) {
                case "Exporo Financing":
                    $type = "finance";

                    break;
                case "Exporo Bestand":
                    $type = "equity";

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
    }
}
