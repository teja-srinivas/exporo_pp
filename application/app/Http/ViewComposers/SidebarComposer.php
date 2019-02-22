<?php

namespace App\Http\ViewComposers;

use App\Models\Agb;
use App\Models\BannerSet;
use App\Models\Bill;
use App\Models\BonusBundle;
use App\Models\CommissionType;
use App\Models\Document;
use App\Models\Project;
use App\Models\Role;
use App\Models\Schema;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User|null
     */
    private $user;

    /**
     * @var Gate
     */
    private $gate;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;


    public function __construct(Request $request, Gate $gate)
    {
        $this->user = $request->user();
        $this->gate = $gate->forUser($this->user);
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $view->with('menu', $this->user === null ? [] : array_filter(array_merge(
            $this->getInternal(),
            $this->getCommissions(),
            $this->getPartner()
        )));
    }

    protected function getPartner()
    {
        if (!$this->gate->check('view partner dashboard')) {
            return [];
        }

        return [
            [
                'title' => 'Meine Daten',
                'links' => [
                    [
                        'title' => 'Abrechnungen',
                        'url' => route('home'),
                        'isActive' => $this->request->routeIs('home'),
                    ],
                    [
                        'title' => 'Einstellungen',
                        'url' => route('users.edit', $this->user),
                        'isActive' => $this->request->routeIs('users.edit'),
                    ],
                    [
                        'title' => 'Dokumente',
                        'url' => route('documents.index'),
                        'isActive' => $this->request->routeIs('documents.index'),
                    ],
                    [
                        'title' => 'Provisionsschema',
                        'url' => route('commission-details'),
                        'isActive' => $this->request->routeIs('commission-details'),
                    ],
                ],
            ],
            [
                'title' => 'Meine Kunden',
                'links' => [
                    [
                        'title' => 'Meine Kunden',
                        'url' => route('users.investors.index', ['user' => $this->user]),
                        'isActive' => $this->request->is(substr(route(
                            'users.investors.index',
                            $this->user,
                            false
                        ), 1)),
                    ],
                    [
                        'title' => 'Investments',
                        'url' => route('users.investments.index', ['user' => $this->user]),
                        'isActive' => $this->request->is(substr(route(
                            'users.investments.index',
                            $this->user,
                            false
                        ), 1)),
                    ],
                ],
            ],
            [
                'title' => 'Werbemittel',
                'links' => [
                    [
                        'title' => 'Banner',
                        'url' => route('affiliate.banners.index'),
                        'isActive' => $this->request->routeIs('affiliate.banners.*'),
                    ],
                    [
                        'title' => 'Links',
                        'url' => route('affiliate.links.index'),
                        'isActive' => $this->request->routeIs('affiliate.links.*'),
                    ],
                    [
                        'title' => 'Mailings',
                        'url' => route('affiliate.mails.index'),
                        'isActive' => $this->request->routeIs('affiliate.mails.*'),
                    ],
                ],
            ],
            $this->user->bonuses()->where('is_overhead', true)->count() > 0 ? [
                'title' => 'Meine Subpartner',
                'links' => [
                    [
                        'title' => 'Meine Subpartner',
                        'url' => route('users.users.index', ['user' => $this->user]),
                        'isActive' => $this->request->is(substr(route(
                            'users.users.index',
                            $this->user,
                            false
                        ), 1)),
                    ],
                    [
                        'title' => 'Subpartner werben',
                        'url' => route('affiliate.child-users'),
                        'isActive' => $this->request->routeIs('affiliate.child-users'),
                    ],
                ],
            ] : [],
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

        if ($this->canList(BannerSet::class)) {
            $links[] = [
                'title' => 'Banner',
                'url' => route('banners.sets.index'),
                'isActive' => $this->request->routeIs('banners.*'),
            ];
        }

        return empty($links) ? [] : [
            [
                'title' => 'Verwaltung',
                'links' => $links,
            ],
        ];
    }

    private function getCommissions()
    {
        $links = [];

        if ($this->canList(Bill::class)) {
            $links[] = [
                'title' => 'Abrechnungen',
                'url' => route('bills.index'),
                'isActive' => $this->request->routeIs('bills.*', 'commissions.index'),
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
                'title' => 'Typen',
                'url' => route('commissions.types.index'),
                'isActive' => $this->request->routeIs('commissions.types.*'),
            ];
        }

        if ($this->canList(BonusBundle::class)) {
            $links[] = [
                'title' => 'Pakete',
                'url' => route('commissions.bundles.index'),
                'isActive' => $this->request->routeIs('commissions.bundles.*'),
            ];
        }

        return empty($links) ? [] : [
            [
                'title' => 'Provisionen',
                'links' => $links,
            ],
        ];
    }

    protected function canList(string $resource): bool
    {
        return $this->gate->any('list', $resource);
    }
}
