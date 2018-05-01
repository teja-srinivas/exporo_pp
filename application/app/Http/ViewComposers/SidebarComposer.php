<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $view->with('menu', array_merge(
            $this->getInternal(),
            $this->getDefault()
        ));
    }

    protected function getDefault()
    {
        return [
            [
                'title' => 'Meine Provisionen',
                'help' => '#',
                'links' => [
                    'home' => 'Übersicht',
                    url('#1') => 'Abrechnungen',
                    url('#2') => 'Provisionen',
                ],
            ],
            [
                'title' => 'Meine Kunden',
                'links' => [
                    url('#1') => 'Kunden werben',
                    url('#2') => 'Meine Kunden',
                    url('#3') => 'Investments',
                    url('#4') => 'Auszahlungen',
                ],
            ],
            [
                'title' => 'Meine Partner',
                'links' => [
                    url('#1') => 'Partner werben',
                    url('#2') => 'Meine Partner',
                ],
            ],
            [
                'title' => 'Werbemittel',
                'links' => [
                    url('#1') => 'Banner',
                    url('#2') => 'Iframes',
                    url('#3') => 'Mailings',
                    url('#4') => 'Links',
                    url('#5') => 'Social Media Content',
                ],
            ],
        ];
    }

    protected function getInternal()
    {
        $links = [];
        $user = auth()->user();

        if ($user->can('create agbs') || $user->can('edit agbs') || $user->can('delete agbs')) {
            $links['#agbs'] = 'AGBs';
        }

        return empty($links) ? [] : [
            [
                'title' => 'System-Verwaltung',
                'links' => $links,
            ],
        ];
    }
}
