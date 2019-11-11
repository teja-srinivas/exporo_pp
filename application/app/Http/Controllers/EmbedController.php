<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Project;

use Illuminate\Http\Request;

class EmbedController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $links = Link::query()
            ->get()
            ->map(function (Link $link) {
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $data = $this->validate($request, [
            'height' => 'required|in:530',
            'width' => 'required|in:770,345',
            'link' => 'required|url',
            'type' => 'required|in:stock,funding,all',
        ]);

        $query = Project::query();
        $query = $query->where('status', 'in funding')->orwhere('status', 'coming soon');
        if ($data['type'] != 'all') {
            $query = $query->where('type', $data['type']);
        }
        $projects = $query->get();

        return view('affiliate.embeds.show', compact(
                'projects',
                'data'
                )
        );
    }
}
