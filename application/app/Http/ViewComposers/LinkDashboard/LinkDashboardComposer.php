<?php

namespace App\Http\ViewComposers\LinkDashboard;

use stdClass;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class LinkDashboardComposer
{
    /** @var Graph[] */
    private $graphs;

    public function __construct()
    {
        $this->graphs = [
            new ByLink(),
            new ByDevice(),
        ];
    }

    public function compose(View $view)
    {
        $graph = $this->getGraph(request('graph', ''));

        $view->with('series', $this->convertToHighcharts($graph));

        $view->with('types', array_map(function (Graph $item) use ($graph) {
            return [
                'isActive' => $item === $graph,
                'name' => $item->getName(),
                'label' => $item->getTitle(),
                'url' => route('affiliate.links.index', [
                    'graph' => $item->getName(),
                ]),
            ];
        }, $this->graphs));
    }

    private function getGraph(string $type): Graph
    {
        /** @ar Graph $graph */
        return Arr::first($this->graphs, static function (Graph $graph) use ($type) {
            return $graph->getName() === $type;
        }, $this->graphs[0]);
    }

    private function convertToHighcharts(Graph $graph): array
    {
        return $graph->plot()->toBase()->get()
            ->mapToGroups(static function (stdClass $record) use ($graph) {
                $label = $graph->getLabel($record->{Graph::GROUP_NAME} ?: 'unknown');

                return [
                    $label => [$record->day, $record->clicks],
                ];
            })->map(function (Collection $group, string $name) {
                return [
                    'name' => $name,
                    'data' => $group,
                ];
            })
            ->values()
            ->toArray();
    }
}
