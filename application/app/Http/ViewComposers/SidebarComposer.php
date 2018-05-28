<?php

namespace App\Http\ViewComposers;

use App\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * @var \App\User|null
     */
    private $user;

    /**
     * @var array|\Illuminate\Http\Request|string
     */
    private $request;


    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    public function compose(View $view)
    {
        $view->with('menu', array_merge(
            $this->getInternal(),
            $this->getPartner()
        ));
    }

    protected function getPartner()
    {
        if (!$this->user->hasRole(Role::PARTNER)) {
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
                    [
                        'title' => 'Abrechnungen',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Provisionen',
                        'url' => '#',
                    ],
                ],
            ],
            [
                'title' => 'Meine Kunden',
                'links' => [
                    [
                        'title' => 'Kunden werben',
                        'url' => '#1'
                    ],
                    [
                        'title' => 'Meine Kunden',
                        'url' => '#2',
                    ],
                    [
                        'title' => 'Investments',
                        'url' => '#3',
                    ],
                    [
                        'title' => 'Auszahlungen',
                        'url' => '#4',
                    ],
                ],
            ],
            [
                'title' => 'Meine Partner',
                'links' => [
                    [
                        'title' => 'Partner werben',
                        'url' => '#1',
                    ],
                    [
                        'title' => 'Meine Partner',
                        'url' => '#2',
                    ],
                ],
            ],
            [
                'title' => 'Werbemittel',
                'links' => [
                    [
                        'title' => 'Banner',
                        'url' => '#1',
                    ],
                    [
                        'title' => 'Iframes',
                        'url' => '#2',
                    ],
                    [
                        'title' => 'Mailings',
                        'url' => '#3',
                    ],
                    [
                        'title' => 'Links',
                        'url' => '#4',
                    ],
                    [
                        'title' => 'Social Media Content',
                        'url' => '#5',
                    ],
                ],
            ],
        ];
    }

    protected function getInternal()
    {
        $links = [];

        if ($this->authorizeResource('agbs')) {
            $links[] = [
                'title' => 'AGBs',
                'url' => route('agbs.index'),
                'isActive' => $this->request->routeIs('agbs.*'),
            ];
        }

        if ($this->authorizeResource('users')) {
            $links[] = [
                'title' => 'Benutzer',
                'url' => route('users.index'),
                'isActive' => $this->request->routeIs('users.*'),
            ];
        }

        if ($this->authorizeResource('roles') || $this->authorizeResource('permissions')) {
            $links[] = [
                'title' => 'Berechtigungen',
                'url' => route('authorization.index'),
                'isActive' => $this->request->routeIs('roles.*', 'permissions.*'),
            ];
        }

        if ($this->authorizeResource('documents')) {
            $links[] = [
                'title' => 'Dokumente',
                'url' => route('documents.index'),
                'isActive' => $this->request->routeIs('documents.*'),
            ];
        }

        return empty($links) ? [] : [
            [
                'title' => 'System-Verwaltung',
                'links' => $links,
            ],
        ];
    }

    protected function authorizeAny(string ...$permissions): bool
    {
        return Gate::forUser($this->user)->any($permissions);
    }

    protected function authorizeResource(string $resource): bool
    {
        return $this->authorizeAny("view $resource", "create $resource", "edit $resource", "delete $resource");
    }
}
