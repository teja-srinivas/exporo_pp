<?php

namespace App\Http\ViewComposers;

use App\Models\Agb;
use App\Models\Bill;
use App\Models\CommissionType;
use App\Models\Document;
use App\Models\Project;
use App\Models\Role;
use App\Models\Schema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * @var \App\Models\User|null
     */
    private $user;

    /**
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    private $gate;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;


    public function __construct(Request $request)
    {
        $this->user = auth()->user();
        $this->gate = Gate::forUser($this->user);
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $view->with('menu', $this->user === null ? [] : array_merge(
            $this->getInternal(),
            $this->getPartner()
        ));
    }

    protected function getPartner()
    {
        if (!$this->gate->check('view partner dashboard')) {
            return [];
        }

        return [
            [
                'title' => 'Meine Provisionen',
                'help' => '#',
                'links' => [
                    [
                        'title' => 'Übersicht',
                        'url' => route('home'),
                        'isActive' => $this->request->routeIs('home'),
                    ],
                ],
            ],
            [
                'title' => 'Meine Kunden',
                'links' => [
                    [
                        'title' => 'Investoren',
                        'url' => route('users.investors.index', ['user' => $this->user]),
                        'isActive' => $this->request->is(substr(route(
                            'users.investors.index', $this->user, false
                        ), 1)),
                    ],
                    [
                        'title' => 'Investments',
                        'url' => route('users.investments.index', ['user' => $this->user]),
                        'isActive' => $this->request->is(substr(route(
                            'users.investments.index', $this->user, false
                        ), 1)),
                    ],
                ],
            ],
            [
                'title' => 'Werbemittel',
                'links' => [
                    [
                        'title' => 'Links',
                        'url' => route('affiliate.links'),
                        'isActive' => $this->request->routeIs('affiliate.links'),
                    ],
                    [
                        'title' => 'Mailings',
                        'url' => route('affiliate.mails'),
                        'isActive' => $this->request->routeIs('affiliate.mails'),
                    ],
                ],
            ],
        ];
    }

    protected function getInternal()
    {
        $links = [];

        if ($this->canList(Agb::class)) {
            $links[] = [
                'title' => 'AGBs',
                'url' => route('agbs.index'),
                'isActive' => $this->request->routeIs('agbs.*'),
            ];
        }

        if ($this->canList(User::class)) {
            $links[] = [
                'title' => 'Benutzer',
                'url' => route('users.index'),
                'isActive' => $this->request->routeIs('users.*'),
            ];
        }

        if ($this->canList(Role::class)) {
            $links[] = [
                'title' => 'Berechtigungen',
                'url' => route('authorization.index'),
                'isActive' => $this->request->routeIs('authorization.*', 'roles.*', 'permissions.*'),
            ];
        }

        if ($this->canList(Document::class)) {
            $links[] = [
                'title' => 'Dokumente',
                'url' => route('documents.index'),
                'isActive' => $this->request->routeIs('documents.*'),
            ];
        }

        if ($this->canList(Project::class)) {
            $links[] = [
                'title' => 'Projekte',
                'url' => route('projects.index'),
                'isActive' => $this->request->routeIs('projects.*'),
            ];
        }

        if ($this->canList(Bill::class)) {
            $links[] = [
                'title' => 'Abrechnungen',
                'url' => route('bills.index'),
                'isActive' => $this->request->routeIs('bills.*', 'commissions.*'),
            ];
        }

        if ($this->canList(Schema::class)) {
            $links[] = [
                'title' => 'Schemata',
                'url' => route('schemas.index'),
                'isActive' => $this->request->routeIs('schemas.*'),
            ];
        }

        if ($this->canList(CommissionType::class)) {
            $links[] = [
                'title' => 'Provisionstypen',
                'url' => route('commissionTypes.index'),
                'isActive' => $this->request->routeIs('commissionTypes.*'),
            ];
        }

        return empty($links) ? [] : [
            [
                'title' => 'System-Verwaltung',
                'links' => $links,
            ],
        ];
    }

    protected function canList(string $resource): bool
    {
        return $this->gate->any('list', $resource);
    }
}
