<?php

namespace App\Http\ViewComposers;

use App\Models\Agb;
use App\Models\Banner;
use App\Models\BannerSet;
use App\Models\Bill;
use App\Models\BonusBundle;
use App\Models\CommissionType;
use App\Models\Document;
use App\Models\Link;
use App\Models\Mailing;
use App\Models\Project;
use App\Models\Role;
use App\Models\Schema;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * @var Authenticatable|User|null
     */
    private $user;

    /**
     * @var Gate
     */
    private $gate;

    /**
     * @var Request
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
        clock()->startEvent('app.sidebar', 'Rendering sidebar');

        $view->with('menu', $this->filter($this->buildMenu()));

        clock()->endEvent('app.sidebar');
    }

    protected function buildMenu()
    {
        if ($this->user === null) {
            return [];
        }

        $dashboard = $this->gate->check('features.users.dashboard');

        return [
            [
                'title' => 'Verwaltung',
                'links' => [
                    [
                        'title' => 'AGBs',
                        'url' => route('agbs.index'),
                        'isActive' => $this->request->routeIs('agbs.*'),
                        'isAllowed' => $this->canList(Agb::class),
                    ],
                    [
                        'title' => 'Benutzer',
                        'url' => route('users.index'),
                        'isActive' => $this->request->routeIs('users.*'),
                        'isAllowed' => $this->canList(User::class),
                    ],
                    [
                        'title' => 'Berechtigungen',
                        'url' => route('authorization.index'),
                        'isActive' => $this->request->routeIs('authorization.*', 'roles.*', 'permissions.*'),
                        'isAllowed' => $this->canList(Role::class),
                    ],
                    [
                        'title' => 'Dokumente',
                        'url' => route('documents.index'),
                        'isActive' => $this->request->routeIs('documents.*'),
                        'isAllowed' => $this->canList(Document::class),
                    ],
                    [
                        'title' => 'Projekte',
                        'url' => route('projects.index'),
                        'isActive' => $this->request->routeIs('projects.*'),
                        'isAllowed' => $this->canList(Project::class),
                    ],
                    [
                        'title' => 'Banner',
                        'url' => route('banners.sets.index'),
                        'isActive' => $this->request->routeIs('banners.*'),
                        'isAllowed' => $this->canList(BannerSet::class),
                    ],
                ],
            ],

            [
                'title' => 'Provisionen',
                'links' => [
                    [
                        'title' => 'Abrechnungen',
                        'url' => route('bills.index'),
                        'isActive' => $this->request->routeIs('bills.*', 'commissions.index'),
                        'isAllowed' => $this->canList(Bill::class),
                    ],
                    [
                        'title' => 'Schemata',
                        'url' => route('schemas.index'),
                        'isActive' => $this->request->routeIs('schemas.*'),
                        'isAllowed' => $this->canList(Schema::class),
                    ],
                    [
                        'title' => 'Typen',
                        'url' => route('commissions.types.index'),
                        'isActive' => $this->request->routeIs('commissions.types.*'),
                        'isAllowed' => $this->canList(CommissionType::class),
                    ],
                    [
                        'title' => 'Pakete',
                        'url' => route('commissions.bundles.index'),
                        'isActive' => $this->request->routeIs('commissions.bundles.*'),
                        'isAllowed' => $this->canList(BonusBundle::class),
                    ],
                ],
            ],

            [
                'title' => 'Meine Daten',
                'links' => array_filter([
                    [
                        'title' => 'Abrechnungen',
                        'url' => route('home'),
                        'isActive' => $this->request->routeIs('home'),
                        'isAllowed' => $dashboard,
                    ],
                    [
                        'title' => 'Einstellungen',
                        'url' => route('users.edit', $this->user),
                        'isActive' => $this->request->routeIs('users.edit'),
                    ],
                    [
                        'title' => 'Dokumente',
                        'url' => route('users.documents.index', $this->user),
                        'isActive' => $this->request->routeIs('users.documents.index'),
                    ],
                    [
                        'title' => 'Provisionsschema',
                        'url' => route('commission-details'),
                        'isActive' => $this->request->routeIs('commission-details'),
                        'isAllowed' => $dashboard,
                    ],
                ]),
            ],

            [
                'title' => 'Meine Kunden',
                'isAllowed' => $dashboard,
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
                'title' => 'Meine Subpartner',
                'isAllowed' => $this->user->bonuses()->where('is_overhead', true)->exists(),
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
            ],

            [
                'title' => 'Werbemittel',
                'links' => [
                    [
                        'title' => 'Banner',
                        'url' => route('affiliate.banners.index'),
                        'isActive' => $this->request->routeIs('affiliate.banners.*'),
                        'isAllowed' => $this->canList(Banner::class),
                    ],
                    [
                        'title' => 'Links',
                        'url' => route('affiliate.links.index'),
                        'isActive' => $this->request->routeIs('affiliate.links.*'),
                        'isAllowed' => $this->canList(Link::class),
                    ],
                    [
                        'title' => 'Mailings',
                        'url' => route('affiliate.mails.index'),
                        'isActive' => $this->request->routeIs('affiliate.mails.*'),
                        'isAllowed' => $this->canList(Mailing::class),
                    ],
                ],
            ],
        ];
    }

    protected function canList(string $resource): bool
    {
        return $this->gate->check('viewAny', $resource);
    }

    protected function filter(array $buildMenu): array
    {
        return array_filter(array_map(function ($menu) {
            if (!($menu['isAllowed'] ?? true)) {
                return null;
            }

            if (!isset($menu['links'])) {
                return $menu;
            }

            $menu['links'] = $this->filter($menu['links']);

            if (empty($menu['links'])) {
                return null;
            }

            return $menu;
        }, $buildMenu));
    }
}
